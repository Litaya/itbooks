<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource', function (Blueprint $table) {
			$table->increments('id')->index();
	        $table->string("title", 30);
			$table->string('file_upload');
            $table->integer('owner_user_id');               // 上传人
            $table->integer('owner_book_id')->nullable();   // 如果是课件的话，属于某本书
            $table->string('access_role')->default('ALL');     // 向哪些角色开放该资源
	        $table->integer('credit')->default(0);
            $table->text('description')->nullable();
            $table->string('type', 20)->nullable()->default('未知');
            $table->string('json_data')->nullable();
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
        Schema::drop('resource');
    }
}
