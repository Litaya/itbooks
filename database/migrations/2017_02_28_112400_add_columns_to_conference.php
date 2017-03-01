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
            $table->string('location')->after('time');
            $table->string('host')->after('location')->nullable();
            $table->string('detail_url')->after('host')->nullable();
            $table->text('json')->after('description')->nullable();
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
            $table->dropColumn(['time', 'json', 'location', 'host', 'created_at', 'updated_at']);
        });
    }
}
