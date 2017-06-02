<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('menu',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('title');
		    $table->text('json');
		    $table->integer('status')->comment('当前状态：0未启用；1正在使用');
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
	    Schema::drop("menu");
    }
}
