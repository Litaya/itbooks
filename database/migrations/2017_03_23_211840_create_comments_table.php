<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('comment', function(Blueprint $table){
		    $table->increments('id');
		    $table->unsignedInteger('user_id');
		    $table->integer('target_type')->comment('target_type表示评论的对象，0:备用; 1:文章; 2:资源; 3:图书');
		    $table->integer('comment_type')->comment('comment_type表示评论的类型，0:备用，1:正常评论, 2:回复');
		    $table->unsignedInteger('target_id')->comment('评论对象的id');
		    $table->unsignedInteger('reply_id')->comment('回复的评论');
		    $table->string('content',512)->comment('评论内容');
		    $table->string('status')->comment('是否审核通过 0:等待审核，1:通过, 2:被用户删除, 3:被管理员删除');
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
	    Schema::drop('comment');
    }
}
