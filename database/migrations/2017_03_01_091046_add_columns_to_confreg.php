<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToConfreg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conference_register', function (Blueprint $table) {
            $table->string('name', 10)->after('conference_id');
            $table->string('school', 20)->after('name');
            $table->string('position', 10)->after('school');
            $table->string('job_title', 10)->after('position');
            $table->string('phone', 15)->after('job_title');
            $table->string('email', 48)->after('phone');
            $table->string('invoice_title', 127)->after('email');
            $table->string('mail_address')->after('invoice_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conference_register', function (Blueprint $table) {
            $table->dropColumn(['name', 'school', 'position', 'job_title', 'phone', 'email', 'invoice_title', 'mail_address']);
        });
    }
}
