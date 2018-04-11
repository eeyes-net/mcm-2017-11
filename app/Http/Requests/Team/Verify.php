<?php

namespace App\Http\Requests\Team;

use App\Exceptions\EvilInputException;
use App\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class Verify extends FormRequest
{
    public function authorize()
    {
        throw new \App\Exceptions\EvilInputException('调用被禁止的 {team}/verify 路由');
        return true;
    }

    public function rules()
    {
        return [];
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
            } elseif ($team_user->pivot->position === Team::USER_POSITION_LEADER) {
                throw new EvilInputException(__('用户（ID = :user_id）作为队长同意加入队伍（ID = :team_id）', [
                    'user_id' => $user->id,
                    'team_id' => $team->id,
                ]));
            } elseif ($team_user->pivot->status === Team::USER_STATUS_VERIFIED) {
                $validator->errors()->add('team.exists', '您已经同意加入此队伍');
            }
        });
    }
}
