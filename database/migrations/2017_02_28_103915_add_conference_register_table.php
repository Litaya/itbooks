<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConferenceRegisterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("conference_register", function(Blueprint $table){
            $table->increments("id")->index();
            $table->integer("user_id")->index();
            $table->integer("conference_id")->index();
	        $table->string('name', 10);
	        $table->string('school', 20);
	        $table->string('position', 10);
	        $table->string('job_title', 10);
	        $table->string('phone', 15);
	        $table->string('email', 48);
	        $table->string('invoice_title', 127);
	        $table->string('mail_address');
	        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("conference_register");
    }
}
