<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTmpCertTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		// 存储用户已经填写但未提交的申请记录
		Schema::create('tmp_cert',function(Blueprint $table){
			$table->increments('id');
			$table->string("realname", 8);
			$table->string('cert_name');		   // TEACHER:教师, AUTHOR:作者
			$table->unsignedInteger('user_id');
			$table->integer('status')->default(0); // 0:进行中，1:审核通过，2:审核不通过，-1:关闭
			$table->string('message')->default('');
			$table->string("img_upload")->nullable();
			$table->text("json_content")->nullable();
			$table->string("workplace", 50);

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
        //
    }
}
