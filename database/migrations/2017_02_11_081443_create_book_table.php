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
			$table->string('isbn',13);
			$table->string('name',511);
			$table->float('price');
			$table->integer('department_id')->unsigned();
			$table->string('product_number');
			$table->string('editor_name');
			$table->string('authors');
			$table->integer('type')->comment('0:未知,1:教材类;2:非教材类');
			$table->integer('weight')->default(0);
			$table->string("img_upload")->nullable();
			$table->string("kj_url")->nullable();
			$table->timestamp('publish_time'); // 出版时间
			$table->timestamps();

			// $table->foreign('department_id')->references('id')->on('department');
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
