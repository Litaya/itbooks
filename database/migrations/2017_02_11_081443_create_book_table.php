<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('book',function(Blueprint $table){
			$table->increments('id')->index();
			$table->string('isbn',13)->unique();
			$table->string('name');
			$table->float('price');
			$table->integer('department_id')->unsigned();
			$table->string('department_name');
			$table->string('product_number');
			$table->integer('editor_id');
			$table->string('editor_name');
			$table->string('authors');
			$table->integer('type')->comment('0:未知,1:教材类;2:非教材类');
			$table->timestamp('public_time'); // 出版时间
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
		Schema::drop('book');
	}
}
