<?php

namespace App\Http\Requests\Recruit;

use App\Exceptions\EvilInputException;
use App\Recruit;
use App\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
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
            $user = Auth::user();
            $recruit = $this->route('recruit');
            if (!$recruit || !($recruit instanceof Recruit)) {
                $validator->errors()->add('recruit.exists', '招募不存在或您不属于此招募的队伍');
            } else {
                /** @var \App\User $recruit_team_user */
                $recruit_team_user = $recruit->team->users()->find($user->id);
                if (!$recruit_team_user) {
                    $validator->errors()->add('recruit.exists', '招募不存在或您不属于此招募的队伍');
                } elseif ($recruit_team_user->pivot->position !== Team::USER_POSITION_LEADER) {
                    $validator->errors()->add('team_id.position', '您不是队长');
                }
            }
        });
    }
}
