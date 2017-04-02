<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoriteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('favorite', function(Blueprint $table){
		    $table->increments('id');
		    $table->unsignedInteger('user_id');
		    $table->unsignedInteger('target_id')->comment('收藏对象的id');
		    $table->integer('target_type')->comment('target_type表示收藏的对象，0:备用; 1:文章; 2:资源; 3:图书');
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
	    Schema::drop('favorite');
    }
}
