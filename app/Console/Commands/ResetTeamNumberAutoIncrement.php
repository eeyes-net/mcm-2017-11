<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ResetTeamNumberAutoIncrement extends Command
{
    protected $signature = 'mcm:reset-team-number-auto-increment {--yes}';
    protected $description = 'Reset team number auto increment to 1';

    public function handle()
    {
        if ($this->option('yes') || $this->confirm('是否确定重置队伍编号计数器？')) {
            Storage::drive('local')->delete('team_number_auto_increment');
        }
    }
}
