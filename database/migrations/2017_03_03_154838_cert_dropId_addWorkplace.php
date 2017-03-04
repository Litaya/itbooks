<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CertDropIdAddWorkplace extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certification', function (Blueprint $table) {
            $table->dropColumn("id_number");
            $table->string("workplace", 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certification', function (Blueprint $table) {
            $table->dropColumn("workplace");
            $table->string("id_number", 30);
        });
    }
}
