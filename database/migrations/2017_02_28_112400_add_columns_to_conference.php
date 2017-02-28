<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToConference extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conference', function (Blueprint $table) {
            $table->timestamps();
            $table->date('time')->after('name');
            $table->text('json')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conference', function (Blueprint $table) {
            $table->dropColumn(['time', 'json', 'created_at', 'updated_at']);
        });
    }
}
