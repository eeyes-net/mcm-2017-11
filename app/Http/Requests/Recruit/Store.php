<?php

namespace App\Http\Requests\Recruit;

use App\Exceptions\EvilInputException;
use App\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class Store extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'team_id' => 'required|integer',
            'tags' => 'array|between:1,3|' . 'in:' . implode(',', config('mcm.recruit_tags', [])),
            'description' => 'required|string|max:4096',
            'contact' => 'required|string|max:255',
        ];
    }

    public function attributes()
    {
        return [
            'team_id' => '队伍',
            'tags' => '招募类型',
            'description' => '队伍描述',
            'contact' => '联系方式',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            $user = Auth::user();
            $team_id = $this->query('team_id');
            /** @var \App\Team $team */
            $team = $user->teams()->find($team_id);
            if (!$team) {
                $validator->errors()->add('team_id', '队伍不存在或您不属于此队伍');
            } elseif ($team->pivot->position !== Team::USER_POSITION_LEADER) {
                $validator->errors()->add('team_id.position', '您不是队长');
            }

            if (!$validator->errors()->isEmpty()) {
                return;
            }

            $recruits_count = $team->recruits()->count();
            $recruits_count_limit = config('mcm.team_recruits_count_limit', 1);
            if ($recruits_count === $recruits_count_limit) {
                $validator->errors()->add('team_id.users_count', __('当前队伍已达到招募上限 :limit 条，不能发布招募', [
                    'limit' => $recruits_count_limit,
                ]));
            } elseif ($recruits_count > $recruits_count_limit) {
                throw new EvilInputException(__('当前队伍（ID = :team_id）发布招募 :count 条，超过招募上限（:limit 条）', [
                    'team_id' => $team->id,
                    'count' => $recruits_count,
                    'limit' => $recruits_count_limit,
                ]));
            }

            $users_count = $team->users()->count();
            $users_count_limit = config('mcm.team_users_count_limit', 3);
            if ($users_count === $users_count_limit) {
                $validator->errors()->add('team_id.users_count', __('当前队伍已达到人数上限 :limit 人，不能发布招募', [
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
