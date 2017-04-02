<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpandDistrictToUserinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_info', function (Blueprint $table) {
            $table->dropColumn("district_id");
            $table->dropColumn("district_name");
            $table->integer("province_id")->nullable()->after("workplace");
            $table->integer("city_id")->nullable()->after("province_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_info', function (Blueprint $table) {
            $table->dropColumn(["province_id", "city_id"]);
            $table->integer("district_id");
            $table->string("district_name");
        });
    }
}
