<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_module',function (Blueprint $table){
        	$table->increments('id');
	        $table->string('module');
        	$table->string('name');
        	$table->string('weight');
        	$table->integer('status')->default(1)->comment('0：未启用，1：启用');
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
        Schema::drop('wechat_module');
    }
}
