<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("conference", function(Blueprint $table){
            $table->increments('id')->index();
            $table->string('name', 50);
	        $table->date('time');
	        $table->string('location');
	        $table->string('host')->nullable();
	        $table->string('detail_url')->nullable();
	        $table->string('img_upload')->nullable();
	        $table->text('description');
	        $table->text('json')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("conference");
    }
}
