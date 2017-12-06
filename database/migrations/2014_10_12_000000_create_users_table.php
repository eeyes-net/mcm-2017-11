<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
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
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
