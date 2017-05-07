<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBehaviorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_behavior',function (Blueprint $table){
        	$table->increments('id');
        	$table->string('module');
        	$table->unsignedInteger('user_id');
        	$table->string('source')->comment('来自微信对话框/网页端行为');
        	$table->string('uri')->comment('请求的uri');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
