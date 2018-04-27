<?php

namespace App\Console\Commands;

use App\Match;
use App\Team;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearUnappliedTeamNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mcm:clear-unapplied-team-number {--yes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear team number of the teams that did not applied any match';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $matches_id = Match::pluck('id')->toArray();
        $teams_id = DB::table('match_team')->whereIn('match_id', $matches_id)->select('team_id')->distinct()->pluck('team_id')->toArray();
        $all_teams_id = Team::pluck('id')->toArray();
        $delete_teams_id = array_diff($all_teams_id, $teams_id);
        $this->info('没有报名任何竞赛的队伍的ID：' . implode(' ', $delete_teams_id));
        if ($this->option('yes') || $this->confirm('是否确定清除这些队伍的编号？')) {
            Team::whereIn('id', $delete_teams_id)->update([
                'team_id' => '',
            ]);
            $this->info('清除成功');
        }
    }
}
