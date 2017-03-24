<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddLabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('add_label', function(Blueprint $table){
		    $table->increments('id');
		    $table->unsignedInteger('label_id');
		    $table->integer('target_type')->comment('target_type表示加标签的对象，0:备用; 1:文章; 2:资源; 3:图书');
		    $table->unsignedInteger('target_id')->comment('加标签对象的id');
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
	    Schema::drop('add_label');
    }
}
