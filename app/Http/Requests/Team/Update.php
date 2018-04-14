<?php

namespace App\Http\Requests\Team;

use App\Exceptions\EvilInputException;
use App\Match;
use App\Team;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class Update extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'users' => 'array|max:' . (config('mcm.team_users_count_limit') - 1),
        ];
    }

    public function attributes()
    {
        return [
            'users' => '队员',
            'stu_id' => '学号',
            'name' => '姓名',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            /** @var \App\Team $team */
            $team = $this->route('team');
            /** @var \App\User $user */
            $user = $this->user();
            /** @var \App\User $team_user */
            $team_user = $team->users()->find($user->id);
            if (!$team_user) {
                $validator->errors()->add('team.exists', '您不属于此队伍');
            } elseif ($team_user->pivot->position !== Team::USER_POSITION_LEADER) {
                $validator->errors()->add('team.position', '您不是队长');
            }

            if (!$validator->errors()->isEmpty()) {
                return;
            }

            $user_teams_count_limit = config('mcm.user_teams_count_limit', 10);

            $team_open_matches_id = $team->matches()->where('status', 'open')->pluck('matches.id');
            $request_users = $this->post('users');
            $users_id = [];
            foreach ($request_users as $i => $request_user) {
                if (empty($request_user['stu_id']) || empty($request_user['name'])) {
                    continue;
                }
                if (!User::whereStuId($request_user['stu_id'])->exists()) {
                    $validator->errors()->add('users.' . $i . '.stu_id', __('学号 :stu_id 不存在或未登录过此网站', [
                        'stu_id' => $request_user['stu_id'],
                    ]));
                } else {
                    $team_user = User::whereStuId($request_user['stu_id'])
                        ->whereName($request_user['name'])
                        ->first();
                    if (!$team_user) {
                        $validator->errors()->add('users.' . $i . '.name', __('学号 :stu_id 与姓名 :name 不匹配', [
                            'stu_id' => $request_user['stu_id'],
                            'name' => $request_user['name'],
                        ]));
                    } else {
                        if ($team_user->id === $user->id) {
                            $validator->errors()->add('users.' . $i . '.name', '请勿在队员列表中填写您自己（队长）');
                        } elseif (in_array($team_user->id, $users_id)) {
                            $validator->errors()->add('users.' . $i . '.name', __('学号 :stu_id 姓名 :name 重复提交', [
                                'stu_id' => $request_user['stu_id'],
                                'name' => $request_user['name'],
                            ]));
                        } else {
                            $user_teams_count = $team_user->teams()->count();
                            if ($user_teams_count > $user_teams_count_limit) {
                                throw new EvilInputException(__('用户（ID = :user_id）的队伍数 :count 个，超过上限 :limit，操作者（ID = :op_user_id）', [
                                    'user_id' => $team_user->id,
                                    'count' => $user_teams_count,
                                    'limit' => $user_teams_count_limit,
                                    'op_user_id' => $team_user->id,
                                ]));
                            } else {
                                $team_user_matches_ok = true;
                                foreach ($team_open_matches_id as $match_id) {
                                    // 报名了比赛，并且不是在本队伍
                                    if ($team_user->isAppliedMatch($match_id) && $team_user->matches_id[$match_id] !== $team->id) {
                                        $validator->errors()->add('user.' . $i . '.teams.matches', __('学号 :stu_id 姓名 :name 已代表其他队伍参加了比赛 :match_title', [
                                            'stu_id' => $request_user['stu_id'],
                                            'name' => $request_user['name'],
                                            'match_title' => Match::find($match_id, ['title'])->title,
                                        ]));
                                        $team_user_matches_ok = false;
                                    }
                                }
                                if ($team_user_matches_ok) {
                                    $users_id[] = $team_user->id;
                                }
                            }
                        }
                    }
                }
            }

            if (!$validator->errors()->isEmpty()) {
                return;
            }

            $this->request->set('users_id', $users_id);
        });
    }
}
