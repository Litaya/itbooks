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
			$table->string('openid')->nullable()->index();
			$table->string('username');
			$table->string('email')->unique()->nullable();
			$table->integer('email_status')->default(0)->comment('0: 未验证; 1: 验证通过');
			$table->string('password')->nullable();
			$table->integer('credits')->default(0)->comment('积分');
			$table->string('permission_string')->default('');
			$table->string('certificate_as')->default('');
			$table->unsignedInteger('information_id')->nullable();
			$table->tinyInteger('subscribed')->default(0);
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
