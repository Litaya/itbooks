<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id');                     // 用户ID
            $table->string('role', 32);                     // 管理员角色-权限
            $table->integer('department_id')->nullable();   // 部门管理员需要
            $table->integer('district_id')->nullable();     // 地区代表需要
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
        Schema::drop('admin');
    }
}
