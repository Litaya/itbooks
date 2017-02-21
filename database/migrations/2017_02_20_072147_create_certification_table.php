<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificationTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('certification',function(Blueprint $table){
			$table->increments('id');
			$table->string('cert_name');		   // TEACHER:教师, AUTHOR:作者  
			$table->unsignedInteger('user_id');
			$table->integer('status')->default(0); // 0:进行中，1:审核通过，2:审核不通过，-1:关闭
			$table->string('message')->default('');

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
		Schema::drop('certification');
	}
}
