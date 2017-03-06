<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKejianToBook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("book", function(Blueprint $table){
            $table->string("kj_url")->nullable()->after("img_upload");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("book", function(Blueprint $table){
            $table->dropColumn("kj_url");
        });
    }
}
