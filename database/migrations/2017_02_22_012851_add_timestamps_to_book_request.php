<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampsToBookRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_request', function (Blueprint $table) {
            $table->date("created_at")->nullable();
            $table->date("updated_at")->after("created_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_request', function (Blueprint $table) {
            $table->dropColumn(["updated_at", "created_at"]);
        });
    }
}
