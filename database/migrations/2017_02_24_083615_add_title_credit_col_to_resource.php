<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitleCreditColToResource extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resource', function (Blueprint $table) {
            $table->string("title", 30)->after("id");
            $table->integer('credit')->default(0)->after("access_role");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resource', function (Blueprint $table){
            $table->dropColumn(["title", "credit"]);
        });
    }
}
