<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImgcontentToCertification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certification', function (Blueprint $table) {
            $table->string("img_upload")->after("message")->nullable();
            $table->text("json_content")->after("img_upload")->nullable();
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
            $table->dropColumn(["img_upload", "json_content"]);
        });
    }
}
