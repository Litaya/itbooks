<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('label', function(Blueprint $table){
		    $table->increments('id');
		    $table->string('name',64);
		    $table->unsignedInteger('user_id',64)->comment('用户创建的标签，0表示管理员创建的');
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
	    Schema::drop('label');
    }
}
