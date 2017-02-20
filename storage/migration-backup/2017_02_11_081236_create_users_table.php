<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user', function (Blueprint $table) {
			$table->increments('id')->index();
			$table->string('openid')->index();
			$table->string('name');
			$table->string('email')->nullable();
			$table->integer('email_status')->default(0)->comment('0: 未验证; 1: 验证通过');
			$table->string('password');
			$table->integer('credits')->default(0)->comment('积分');
			$table->integer('type')->default(0)->comment('0: 未知; 1: 教师; 2: 作者; 3: 学生; 4: 职员');
			$table->timestamps();
			$table->rememberToken();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user');
	}
}
