<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRealnameIdToCert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certification', function(Blueprint $table){
            $table->string("realname", 8)->after("id");
            $table->string("id_number", 20)->after("realname");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certification', function(Blueprint $table){
            $table->dropColumn("realname");
            $table->dropColumn("id_number");
        });
    }
}
