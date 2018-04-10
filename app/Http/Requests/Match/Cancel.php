<?php

namespace App\Http\Requests\Match;

use App\Exceptions\EvilInputException;
use App\Match;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class Cancel extends FormRequest
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
            $match = $this->route('match');
            if (!$match || !($match instanceof Match)) {
                $validator->errors()->add('match.exists', '比赛不存在');
            } elseif (!$match->is_available) {
                $validator->errors()->add('match.available', '比赛已截止或暂时不开放');
            }

            if (!$validator->errors()->isEmpty()) {
                return;
            }
            $user = Auth::user();
            $team_ids = $user->teams()->pluck('teams.id')->toArray();
            $teams = $match->teams()->whereIn('teams.id', $team_ids)->get();
            $teams_count = $teams->count();
            if ($teams_count > 1) {
                throw new EvilInputException(__('当前用户有多个队伍报名同一场比（用户名 :username，比赛 ID：:match_id）', [
                    'username' => $user->username,
                    'match_id' => $match->id,
                ]));
            } elseif ($teams_count <= 0) {
                $validator->errors()->add('user.match_team', '您未报名这场比赛');
            }

            if (!$validator->errors()->isEmpty()) {
                return;
            }
            $team = $teams->first();
            if (!$team->isLeader($user)) {
                $validator->errors()->add('user.match_team.position', '您不是队长');
            }

            if (!$validator->errors()->isEmpty()) {
                return;
            }
            $this->request->set('team', $team);
        });
    }
}
