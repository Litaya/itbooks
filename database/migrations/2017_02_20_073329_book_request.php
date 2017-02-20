<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BookRequest extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('book_request',function (Blueprint $table){
			$table->increments('id');
			$table->unsignedInteger('book_id');
			$table->unsignedInteger('user_id');
			$table->string('shipping_id')->nullable()->comment('运单号');
			$table->integer('status')->default(0)->comment('0:未审核; 1:审核通过; 2审核未通过');
			$table->string('message')->default('')->comment('审核未通过时,管理员的反馈信息');
			$table->string('address');
			$table->string('phone');
			$table->string('receiver');

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
		Schema::drop('book_request');
	}
}
