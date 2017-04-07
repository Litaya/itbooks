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
		    $table->string("media_id",127)->comment('图文素材的id');
            $table->string("thumb_media_id",127)->comment('封面图片media_id');
		    $table->string('cover_path')->comment('图文消息的封面图片本地地址');
		    $table->tinyInteger('show_cover_pic')->comment('是否显示封面，0为false，即不显示，1为true，即显示');
		    $table->string('author',31)->comment('作者');
		    $table->string('digest')->comment('摘要');
		    $table->text('content')->nullable()->comment('正文');
		    $table->string("url")->comment('图文消息的URL');
		    $table->string("content_source_url")->default('')->comment('图文消息的原文地址');
		    $table->integer('reading_quantity')->default(0)->comment('阅读量');
		    $table->unsignedInteger('category_id')->default(0)->comment('文章类别');
		    $table->timestamp('wechat_update_time')->comment('文章的上传更新时间');
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
