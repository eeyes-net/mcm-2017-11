<?php

namespace App\Http\Requests\Match;

use App\Match;
use App\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class Apply extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'team_id' => 'required|integer',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            $match = $this->route('match');
            if (!$match || !($match instanceof Match)) {
                $validator->errors()->add('match.exists', '比赛不存在');
            } elseif (!$match->is_available) {
                $validator->errors()->add('match.available', '比赛已截止或暂时不开放');
            }

            $user = Auth::user();
            $team_id = $this->request->get('team_id');
            /** @var Team $team */
            $team = $user->teams()->find($team_id);
            if (!$team) {
                $validator->errors()->add('team_id.exists', '队伍不存在或您不属于此队伍');
            } elseif ($team->pivot->position !== Team::USER_POSITION_LEADER) {
                $validator->errors()->add('team_id.position', '您不是队长');
            }

            if ($validator->errors()->isEmpty()) {
                if ($match->teams()->find($team->id)) {
                    $validator->errors()->add('team_id.applied', '此队伍已报名该比赛');
                }
            }

            if ($validator->errors()->isEmpty()) {
                foreach ($team->users as $team_user) {
                    if ($user->isAppliedMatch($match)) {
                        $validator->errors()->add('team_id.user_applied', __('用户 :name 已在其他队伍报名此比赛', [
                            'name' => $team_user->name,
                        ]));
                    }
                }
            }
        });
    }
}
