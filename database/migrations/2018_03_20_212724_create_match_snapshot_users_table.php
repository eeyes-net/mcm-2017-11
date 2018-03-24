<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchSnapshotUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_snapshot_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_snapshot_id')->unsigned();
            $table->foreign('match_snapshot_id')->references('id')->on('match_snapshots');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('team_id')->unsigned();
            $table->foreign('team_id')->references('id')->on('teams');
            $table->string('team_number', 50)->comment('队伍编号');
            $table->string('username', 190)->comment('用户名NetID');
            $table->text('stu_id')->comment('学号');
            $table->text('name')->comment('姓名');
            $table->text('department')->comment('学院');
            $table->text('major')->comment('专业');
            $table->text('class')->comment('班级');
            $table->text('contact')->comment('联系方式');
            $table->string('email', 190)->comment('邮箱');
            $table->string('position', 20)->comment('身份');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_snapshot_users');
    }
}
