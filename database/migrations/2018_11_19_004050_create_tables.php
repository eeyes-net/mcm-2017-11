<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 190)->unique()->comment('用户名NetID');
            $table->text('stu_id')->comment('学号');
            $table->text('name')->comment('姓名');
            $table->text('department')->comment('学院');
            $table->text('major')->comment('专业');
            $table->text('class')->comment('班级');
            $table->text('contact')->comment('联系方式');
            $table->string('email', 190)->unique()->comment('邮箱');
            $table->string('password', 255)->comment('bcrypt密码');
            $table->string('group', 20)->comment('用户组');
            $table->text('experience')->comment('参赛与获奖经历');
            $table->text('coach_name')->comment('教练姓名');
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title')->comment('标题');
            $table->text('content')->comment('内容');
            $table->timestamps();
        });
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title')->comment('标题');
            $table->dateTime('expired_at')->comment('截止日期');
            $table->string('status', 20)->comment('状态');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('team_id', 50)->comment('队伍编号');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('team_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_id')->unsigned();
            $table->foreign('team_id')->references('id')->on('teams');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('position', 20)->comment('职位');
            $table->string('status', 20)->comment('状态');
            $table->timestamps();
            $table->unique(['team_id', 'user_id']);
        });
        Schema::create('match_team', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_id')->unsigned();
            $table->foreign('match_id')->references('id')->on('matches');
            $table->integer('team_id')->unsigned();
            $table->foreign('team_id')->references('id')->on('teams');
            $table->timestamps();
            $table->unique(['match_id', 'team_id']);
        });
        Schema::create('recruits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_id')->unsigned();
            $table->foreign('team_id')->references('id')->on('teams');
            $table->text('tags')->comment('招募类型');
            $table->text('members')->comment('当前成员');
            $table->text('description')->comment('队伍描述');
            $table->text('contact')->comment('联系方式');
            $table->timestamps();
        });
        Schema::create('visit_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->default(0);
            $table->string('user_info', 255)->default(0);
            $table->string('path', 1024)->default('');
            $table->string('method', 20)->default('');
            $table->string('ip', 40)->default('');
            $table->string('user_agent', 1024)->default('');
            $table->string('query', 1024)->default('');
            $table->string('body', 4096)->default('');
            $table->integer('response_code')->default(0);
            $table->integer('response_length')->unsigned()->default(0);
            $table->string('response_body', 4096)->default('');
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
        Schema::dropIfExists('visit_logs');
        Schema::dropIfExists('recruits');
        Schema::dropIfExists('match_team');
        Schema::dropIfExists('team_user');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('matches');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('users');
    }
}
