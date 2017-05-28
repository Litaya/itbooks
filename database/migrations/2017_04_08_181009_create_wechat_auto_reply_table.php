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
        	$table->integer('type')->comment('要回复的类型 0: 文字, 1: 图片, 2: 图文');
        	$table->integer('regex_type')->comment('匹配的类型：精确匹配/模糊匹配; 0表示精确匹配, 1表示模糊匹配');
        	$table->string('content',1024)->default("")->comment('如果是文字，存储文字内容； 如果是图片，存储图片地址； 如果是回复图文消息，则此处存储json数组，存储图文消息的id， [1,2]；');
        	$table->integer('trigger_quantity')->default(0);
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
        Schema::drop('wechat_auto_reply');
    }
}
