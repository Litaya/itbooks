<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditorTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('editor',function(Blueprint $table){
			$table->integer('id');
			$table->string('name');
			$table->integer('status');
			$table->integer('office_id')->unsigned();
			$table->timestamps();

			$table->foreign('office_id')->references('id')->on('department');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('editor');
	}
}
