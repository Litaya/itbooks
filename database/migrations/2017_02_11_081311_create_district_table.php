<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('district',function (Blueprint $table){
			$table->smallIncrements('id')->index();
			$table->string('name',90);
			$table->unsignedSmallInteger('parentid');
			$table->char('initial');
			$table->string('initials',8);
			$table->string('pinyin',50);
			$table->string('suffix',8);
			$table->string('code',8);
			$table->unsignedTinyInteger('order');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('district');
	}
}
