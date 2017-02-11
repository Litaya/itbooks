<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepresentativeTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('representative',function(Blueprint $table){
			$table->integer('id');
			$table->string('name');
			$table->integer('status')->comment('0: 审核未通过; 1:审核通过');
			$table->unsignedSmallInteger('district_id');
			$table->string('district_name');
			$table->timestamps();

			$table->foreign('district_id')->references('id')->on('district');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('representative');
	}
}
