<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTeamsTableDropTeamIdUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table('teams', function (Blueprint $table) {
                $table->dropUnique('teams_team_id_unique');
            });
        } catch (QueryException $exception) {
            \Illuminate\Support\Facades\Log::error($exception->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            Schema::table('teams', function (Blueprint $table) {
                $table->unique('team_id');
            });
        } catch (QueryException $exception) {
            \Illuminate\Support\Facades\Log::error($exception->getMessage());
        }
    }
}
