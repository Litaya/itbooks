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
			$table->string('openid', 512)->nullable()->index();
			$table->string('username', 12);
			$table->integer('gender')->default(0)->comment('0 未知;1 男; 2 女');
			$table->string('headimgurl')->default('/img/avatar.png');
			$table->string('email', 40)->unique()->nullable();
			$table->integer('email_status')->default(0)->comment('0: 未验证; 1: 验证通过');
			$table->string('password', 32)->nullable();
			$table->integer('credits')->default(0)->comment('积分');
			$table->string('permission_string', 128)->default('');
			$table->string('certificate_as', 32)->default('');
			$table->unsignedInteger('information_id')->nullable();
			$table->tinyInteger('subscribed')->default(0);
			$table->string('source', 12)->default("未知")->comment('wechat、web_register');
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
