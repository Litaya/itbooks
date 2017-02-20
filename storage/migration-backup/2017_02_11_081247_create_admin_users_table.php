<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admin_user',function(Blueprint $table){
			$table->increments('id')->index();
			$table->string('name',15);
			$table->string('email');
			$table->integer('email_status')->comment('0:验证通过; 1:验证未通过');
			$table->string('password');
			$table->integer('type')->comment('0: 超级管理员; 1: 分社管理员organization_admin; 2: 部门管理员 department_admin; 3: 编辑是管理员 editor_admin; 4: 院校代表; 5: 编辑');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('admin_user');
	}
}
