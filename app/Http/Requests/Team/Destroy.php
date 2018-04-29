<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class Destroy extends FormRequest
{
    public function authorize()
    {
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
            if (!$team->hasUser($user)) {
                $validator->errors()->add('team.exists', '您不属于此队伍');
            }
            if (config('mcm.disallow_leader_leave_team', false)) {
                if ($team->isLeader($user)) {
                    if ($team->matches()->exists()) {
                        $validator->errors()->add('team.position', '目前不允许队长退出队伍，您可以选择移除其他队员');
                    }
                }
            }
        });
    }
}
