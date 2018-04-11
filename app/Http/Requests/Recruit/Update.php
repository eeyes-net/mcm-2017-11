<?php

namespace App\Http\Requests\Recruit;

use App\Exceptions\EvilInputException;
use App\Recruit;
use App\Team;
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
            'tags' => 'array|between:1,3|' . 'in:' . implode(',', config('mcm.recruit_tags', [])),
            'description' => 'required|string|max:4096',
            'contact' => 'required|string|max:255',
        ];
    }

    public function attributes()
    {
        return [
            'tags' => '招募类型',
            'description' => '队伍描述',
            'contact' => '联系方式',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            $user = $this->user();
            $recruit = $this->route('recruit');
            if (!$recruit || !($recruit instanceof Recruit)) {
                $validator->errors()->add('recruit.exists', '招募不存在或您不属于此招募的队伍');
            } else {
                /** @var \App\Recruit $recruit */
                $team = $recruit->team;
                /** @var \App\User $recruit_team_user */
                $recruit_team_user = $team->users()->find($user->id);
                if (!$recruit_team_user) {
                    $validator->errors()->add('recruit.exists', '招募不存在或您不属于此招募的队伍');
                } elseif ($recruit_team_user->pivot->position !== Team::USER_POSITION_LEADER) {
                    $validator->errors()->add('team_id.position', '您不是队长');
                }
            }

            if (!$validator->errors()->isEmpty()) {
                return;
            }

            $recruits_count = $team->recruits()->count();
            $recruits_count_limit = config('mcm.team_recruits_count_limit', 1);
            if ($recruits_count > $recruits_count_limit) {
                throw new EvilInputException(__('当前队伍（ID = :team_id）发布招募 :count 条，超过招募上限（:limit 条）', [
                    'team_id' => $team->id,
                    'count' => $recruits_count,
                    'limit' => $recruits_count_limit,
                ]));
            }

            $users_count = $team->users()->count();
            $users_count_limit = config('mcm.team_users_count_limit', 3);
            if ($users_count === $users_count_limit) {
                $validator->errors()->add('team_id.users_count', __('当前队伍已达到人数上限 :limit 人，请及时删除此条招募', [
                    'limit' => $users_count_limit,
                ]));
            } elseif ($users_count > $users_count_limit) {
                throw new EvilInputException(__('当前队伍（ID = :team_id）人数 :count 人，超过人数上限（:limit 人）', [
                    'team_id' => $team->id,
                    'count' => $users_count,
                    'limit' => $users_count_limit,
                ]));
            }

            if (!$validator->errors()->isEmpty()) {
                return;
            }

            $this->request->set('team', $team);
        });
    }
}
