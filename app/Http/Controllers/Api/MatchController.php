<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Match;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchController extends Controller
{
    public function index()
    {
        return Match::ordered()->paginate();
    }

    public function show(Match $match)
    {
        return $match;
    }

    /**
     * 报名某一比赛
     * @param Request $request
     * @param Match $match
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     * @throws CustomException
     */
    public function apply(Request $request, Match $match)
    {
        if ($match->status !== 'open')
        {
            throw new CustomException(__('比赛 :title 目前不开放', [
                'title' => $match->title,
            ]));
        }
        
        $team_id = $request->post('team_id');
        $user = Auth::user();
        /** @var Team $team */
        $team = $user->teams()->wherePivot('position', 'leader')->find($team_id);
        if (!$team) {
            throw new CustomException('队伍不存在、你不在其中或者您不是队长');
        }
        if ($match->teams()->find($team->id)) {
            throw new CustomException('此队伍已报名该比赛');
        }
        /** @var User $team_user */
        $errors = [];
        foreach($team->users as $team_user) {
            /** @var Team $team_user_team */
            foreach ($team_user->teams as $team_user_team) {
                if ($team_user_team->matches()->find($match->id)) {
                    $errors[] = __('用户 :name 已在其他队伍报名此比赛', [
                        'name' => $team_user->name,
                    ]);
                    break;
                }
            }
        }
        if ($errors) {
            throw new CustomException('某用户已在其他队伍报名此比赛', $errors);
        }
        $match->teams()->save($team);
        return $team->load('matches');
    }
}
