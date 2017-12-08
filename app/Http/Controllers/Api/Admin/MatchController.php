<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Match;
use App\Team;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index()
    {
        return Match::latest()->paginate();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'expired_at' => 'required|date',
            'status' => 'required',
        ]);
        if ($data['status'] !== 'open') {
            $data['status'] = 'close';
        }
        $match = Match::create($data);
        return $match;
    }

    public function show(Match $match)
    {
        return $match;
    }

    public function team(Match $match)
    {
        return $match->teams()->paginate();
    }

    public function apply(Request $request, Match $match)
    {
        $team_id = $request->post('team_id');
        $team = Team::find($team_id);
        $match->teams()->save($team);
        return $match;
    }

    public function detach(Match $match, Team $team)
    {
        $match->teams()->detach($team);
        return $match;
    }

    public function update(Request $request, Match $match)
    {
        $data = $request->only([
            'title',
            'expired_at',
            'status',
        ]);
        if (isset($data['status']) && $data['status'] !== 'open') {
            $data['status'] = 'close';
        }
        $match->update($data);
        return $match;
    }

    public function destroy(Match $match)
    {
        $match->delete();
        return $match;
    }
}
