<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use App\Match;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MatchController extends Controller
{
    public function index()
    {
        $applied_matches_id = [];
        if (Auth::check()) {
            $user = Auth::user();
            $applied_matches_id = $user->matches_id;
            $leading_teams_id = $user->leading_teams_id;
        }
        $matches = Cache::tags('matches')->remember('matches_page_' . Paginator::resolveCurrentPage(), 1440, function () {
            return Match::withCount('teams')->ordered()->paginate(12);
        });
        return view('index.match.index', compact(
            'matches',
            'applied_matches_id',
            'leading_teams_id'
        ));
    }

    public function show(Match $match)
    {
        return view('index.match.show', [
            'match' => $match,
        ]);
    }
}
