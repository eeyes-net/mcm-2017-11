<?php

namespace App\Http\Controllers\Api;

use App\Events\MatchTeamCountUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Match\Apply;
use App\Http\Requests\Match\Cancel;
use App\Http\Resources\Match as MatchResource;
use App\Match;

class MatchController extends Controller
{
    public function index()
    {
        $matches = (new Match)->append('is_applied')->withCount('teams')->ordered()->paginate();
        return MatchResource::collection($matches);
    }

    public function show(Match $match)
    {
        return new MatchResource($match);
    }

    /**
     * 报名某一比赛
     *
     * @param \App\Http\Requests\Match\Apply $request
     * @param \App\Match $match
     *
     * @return \App\Http\Resources\Match
     */
    public function apply(Apply $request, Match $match)
    {
        $match->teams()->syncWithoutDetaching($request->post('team_id'));
        event(new MatchTeamCountUpdated());
        return new MatchResource($match);
    }

    /**
     * 取消报名比赛
     *
     * @param \App\Http\Requests\Match\Cancel $request
     * @param \App\Match $match
     *
     * @return \App\Http\Resources\Match
     */
    public function cancel(Cancel $request, Match $match)
    {
        /** @var \App\Team $team */
        $team = $request->get('team');
        $match->teams()->detach([$team->id]);
        event(new MatchTeamCountUpdated());
        return new MatchResource($match);
    }
}
