<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('teacher', function (Blueprint $table) {
			$table->integer('id')->index();
			$table->string('realname');
			$table->integer('school_id')->unsigned();
			$table->string('school');
			$table->string('teacherID');
			$table->integer('status')->default(0)->comment('0:未审核; 1:审核通过; 2审核未通过');
			$table->string('message')->default('')->comment('审核未通过时的信息');

			$table->foreign('school_id')->references('id')->on('school');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('teacher');
	}
}
