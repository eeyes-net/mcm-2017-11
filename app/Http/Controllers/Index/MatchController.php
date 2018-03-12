<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Match;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MatchController extends Controller
{
    public function index()
    {
        $applied_matches_id = [];
        if (Auth::check()) {
            $applied_matches_id = Auth::user()->appliedMatchesId;
        }
        $matches = Cache::tags('matches')->remember('matches' . request('page'), 1440, function () {
            return Match::ordered()->paginate(12);
        });
        return view('index.match.index', [
            'matches' => $matches,
            'applied_matches_id' => $applied_matches_id,
        ]);
    }

    public function show(Match $match)
    {
        return view('index.match.show', [
            'match' => $match,
        ]);
    }
}
