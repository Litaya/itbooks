<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('department',function(Blueprint $table){
			$table->increments('id')->index();
			$table->string('name');
			$table->integer('type')->comment('1: 分社1; 2: 部门101; 3: 编辑室10101');
			$table->integer('principal_id')->nullable();
			$table->string('principal_name')->nullable();
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
		Schema::drop('department');
	}
}
