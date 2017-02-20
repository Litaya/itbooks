<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentAdminTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('department_admin',function(Blueprint $table){
			$table->integer('id');
			$table->string('name');
			$table->integer('status');
			$table->integer('department_id')->unsigned();
			$table->string('department_name');
			$table->timestamps();

			$table->foreign('department_id')->references('id')->on('department');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('department_admin');
	}
}
