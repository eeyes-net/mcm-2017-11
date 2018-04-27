<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArrayResource;
use App\Match;
use App\Recruit;
use App\Team;
use App\User;
use App\VisitLog;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index()
    {
        $latest_match = Match::latest()->first();
        return new ArrayResource([
            'users_count' => User::count(),
            'teams_count' => Team::count(),
            'recruits_count' => Recruit::count(),
            'visit_logs_count' => VisitLog::count(),
            'matches' => \App\Http\Resources\Match::collection(Match::withCount('teams')->ordered()->limit(12)->get()),
            'users_new' => \App\Http\Resources\User::collection(User::latest()->limit(12)->get()),
            'users_growth' => User::selectRaw('COUNT(*) AS `count`, date(`created_at`) AS `created_date`')->groupBy('created_date')->orderByDesc('created_date')->limit(30)
                ->pluck('count', 'created_date')->reverse()->toArray(),
            'match_teams_growth' => [
                'match' => $latest_match,
                'data' => DB::table('match_team')->where('match_id', $latest_match->id)
                    ->selectRaw('COUNT(*) AS `count`, date(`created_at`) AS `created_date`')->groupBy('created_date')->orderByDesc('created_date')->limit(30)
                    ->pluck('count', 'created_date')->reverse()->toArray(),
            ],
            'match_department_users_count' => [
                'match' => $latest_match,
                'data' => User::selectRaw('COUNT(*) AS `count`, `department`')->whereIn('id', array_keys($latest_match->users_id))->groupBy('department')
                    ->pluck('count', 'department')->toArray(),
            ],
        ]);
    }
}
