<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserinfoTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_info', function (Blueprint $table){
			$table->integer('user_id');
			$table->string('phone')->nullable();
			$table->string('qq')->nullable();
			$table->string('realname')->nullable();

			// for teacher、student
			$table->integer('school_id')->nullable();
			$table->string('school_name')->nullable();
			$table->string('school_division')->nullable();
			$table->string('school_title')->nullable();
			$table->text('school_json')->nullable();

			// for author
			$table->string('workplace')->nullable();
			$table->unsignedInteger('book_id')->nullable();

			// for representative
			$table->integer('district_id')->nullable();
			$table->string('district_name')->nullable();

			// for editor、department_admin
			$table->integer('department_id')->nullable();
			$table->string('department_name')->nullable();

			$table->text("address")->nullable();

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
		Schema::drop('user_info');
	}
}
