<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookPopularity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book',function(Blueprint $table){
            $table->integer("favorite_num")->default(0);
            $table->integer("sales_volume")->default(0);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book',function(Blueprint $table){
            $table->dropColumn("favorite_num");
            $table->dropColumn("sales_volume");
        });
    }
}
