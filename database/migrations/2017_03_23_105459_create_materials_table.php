<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('materials', function(Blueprint $table){
		    $table->increments('id');
		    $table->string('title');
		    $table->string("media_id",128)->comment('图文素材的id');
		    $table->string('thumb_media_id',128)->comment('图文消息的封面图片素材id');
		    $table->tinyInteger('show_cover_pic')->comment('是否显示封面，0为false，即不显示，1为true，即显示');
		    $table->string('author',32)->comment('作者');
		    $table->string('digest')->comment('摘要');
		    $table->string("url")->comment('图文消息的URL');
		    $table->string("content_source_url")->comment('图文消息的原文地址');
		    $table->integer('reading_quantity')->comment('阅读量');
		    $table->unsignedInteger('category_id')->comment('文章类别');
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
        Schema::drop('materials');
    }
}
