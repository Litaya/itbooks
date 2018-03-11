<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('order_feedback', function(Blueprint $table){
		    $table->increments('id');                   // id
		    $table->unsignedInteger('book_id');         // book_id
		    $table->string('book_isbn',13);             // book_isbn
		    $table->unsignedInteger('department_id');
		    $table->string('department_name',13);             // book_isbn
		    $table->unsignedInteger('user_id')->nullable();         // user_id
		    $table->string('user_realname')->nullable();            // user_realname
		    $table->unsignedInteger('admin_id')->comment('处理该条记录的管理员id')->nullable();  // admin_id
		    $table->string('admin_name')->comment('处理该条记录的管理员姓名')->nullable();        // admin_name
		    $table->timestamp('order_time');                                      // order_time
		    $table->integer('order_count');                                       // order_count
		    $table->string('image_path');                                         // image_path
		    $table->tinyInteger('status')->default(0)->comment('申请状态, -2: 用户删除申请； -1: 用户取消申请；0: 正在申请；1: 申请通过；2: 申请拒绝; ');
		    $table->string('refuse_message')->comment('拒绝理由')->nullable();                 // refuse_message
		    $table->text('ext')->comment('扩展字段，json格式，需要加东西的时候加在这里')->nullable();
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
        Schema::drop('order_feedback');
    }
}
