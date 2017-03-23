<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Order extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order',function(Blueprint $table){
			$table->increments('id');
			$table->unsignedInteger('book_id');
			$table->unsignedInteger('user_id');
			$table->string('shipping_id')->nullable()->comment('运单号,来自物流运营商');
			$table->float('price')->default(0.0);
			$table->float('cash_paid')->default(0.0);
			$table->float('credits_paid')->default(0.0);
			$table->integer('payment_status')->default(0)->comment('0:未支付, 1:支付成功');
			$table->integer('status')->default(0)->comment('0:用户未提交订单; 1:用户已提交订单; 2:管理员已处理,准备寄送; 3: 管理员已寄送(此时需要填写运单号); 3:签收,订单完成');
			$table->string('message')->default('');

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
		Schema::drop('order');
	}
}
