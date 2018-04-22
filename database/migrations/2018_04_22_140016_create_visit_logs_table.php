<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
    }
}
