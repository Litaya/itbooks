<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatAutoReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_auto_reply',function (Blueprint $table){
        	$table->increments('id');
        	$table->string('regex')->comment('要匹配的正则表达式');
        	$table->string('reply')->comment('要回复的内容');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wechat_auto_reply');
    }
}
