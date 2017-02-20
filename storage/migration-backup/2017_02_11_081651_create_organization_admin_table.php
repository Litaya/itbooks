<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationAdminTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('organization_admin',function(Blueprint $table){
			$table->integer('id');
			$table->string('name');
			$table->integer('status');
			$table->integer('organization_id')->unsigned();
			$table->string('organization_name');
			$table->timestamps();

			$table->foreign('organization_id')->references('id')->on('department');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('organization_admin');
	}
}
