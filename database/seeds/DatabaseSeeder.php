<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('user')->insert([
			'openid' => "o89Rxt6-8Ckqv9I_mXiYWXoqhz3k",
			'username' => "我是测试用户",
			'gender' => 2,
			'permission_string' => 'all',
			'headimgurl' => "http://wx.qlogo.cn/mmopen/hDHfY6iauFKeb08Nu1BL1NvqUzInxE3okLL8iauXUBkNu0tHAz9W1VH9NowZB4KCuw8gIXrFdvWq98Iia6RFwS7RWMg8gVKibdicp/0",
			'email' => 'Zhangxr1221'.'@gmail.com',
			'email_status' => 1,
			'password' => bcrypt('secret')
		]);
	}
}
