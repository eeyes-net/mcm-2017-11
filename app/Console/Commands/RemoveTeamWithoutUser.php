<?php

namespace App\Console\Commands;

use App\Team;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveTeamWithoutUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mcm:remove-team-without-user {--yes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove team without user';

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
        $teams_id = DB::table('team_user')->select('team_id')->distinct()->pluck('team_id')->toArray();
        $all_teams_id = Team::pluck('id')->toArray();
        $delete_teams_id = array_diff($all_teams_id, $teams_id);
        $this->info('没有队员的队伍ID：' . implode(' ', $delete_teams_id));
        if ($this->option('yes') || $this->confirm('是否确定删除这些队伍？')) {
            DB::table('recruits')->whereIn('team_id', $delete_teams_id)->delete();
            DB::table('match_team')->whereIn('team_id', $delete_teams_id)->delete();
            Team::whereIn('id', $delete_teams_id)->delete();
            $this->info('删除成功');
        }
    }
}
