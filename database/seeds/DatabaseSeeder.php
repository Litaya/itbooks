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
			'id'=>1,
			'openid' => "o89Rxt6-8Ckqv9I_mXiYWXoqhz3k",
			'username' => "超级管理员",
			'gender' => 2,
			'permission_string' => 'all',
			'headimgurl' => "http://wx.qlogo.cn/mmopen/hDHfY6iauFKeb08Nu1BL1NvqUzInxE3okLL8iauXUBkNu0tHAz9W1VH9NowZB4KCuw8gIXrFdvWq98Iia6RFwS7RWMg8gVKibdicp/0",
			'email' => 'super'.'@gmail.com',
			'email_status' => 1,
			'subscribed' => 1,
			'password' => bcrypt('secret')
		]);

		DB::table('admin')->insert([
			'id'=>1,
			'username'=> '超级管理员',
			'permission_string' => 'all',
			'certificate_as'    => 'SUPER_ADMIN'
		]);

		DB::table('user')->insert([
			'id'=>3,
			'openid' => "o89Rxt6-8Ckqv9I_mXiYWXoqhz1k",
			'username' => "部门管理员#1",
			'gender' => 1,
			'permission_string' => 'book_curd_d1|bookreq_curd_d1',
			'headimgurl' => "http://wx.qlogo.cn/mmopen/hDHfY6iauFKeb08Nu1BL1NvqUzInxE3okLL8iauXUBkNu0tHAz9W1VH9NowZB4KCuw8gIXrFdvWq98Iia6RFwS7RWMg8gVKibdicp/0",
			'email' => 'department'.'@gmail.com',
			'email_status' => 1,
			'subscribed' => 1,
			'password' => bcrypt('secret')
		]);
		DB::table('admin')->insert([
			'id'=>3,
			'username' => '部门管理员#1',
			'permission_string' => 'book_curd_d1|bookreq_curd_d1',
			'certificate_as'    => 'DEPARTMENT_ADMIN'
		]);

		DB::table('user')->insert([
			'id'=>4,
			'openid' => "o89Rxt6-8Ckqv9I_mXiYWXoqhz1k",
			'username' => "院校代表#1",
			'gender' => 1,
			'permission_string' => 'book_r_p1|bookreq_r_p1',
			'headimgurl' => "http://wx.qlogo.cn/mmopen/hDHfY6iauFKeb08Nu1BL1NvqUzInxE3okLL8iauXUBkNu0tHAz9W1VH9NowZB4KCuw8gIXrFdvWq98Iia6RFwS7RWMg8gVKibdicp/0",
			'email' => 'representative'.'@gmail.com',
			'email_status' => 1,
			'subscribed' => 1,
			'password' => bcrypt('secret')
		]);
		DB::table('admin')->insert([
			'id'=>3,
			'username' => '院校代表#1',
			'permission_string' => 'book_r_p1|bookreq_r_p1',
			'certificate_as'    => 'REPRESENTATIVE'
		]);


		DB::table('user')->insert([
			'openid' => "o89Rxt6-8Ckqv9I_mXiYWXoqhz2k",
			'username' => "普通用户",
			'gender' => 2,
			'permission_string' => '',
			'headimgurl' => "http://wx.qlogo.cn/mmopen/hDHfY6iauFKeb08Nu1BL1NvqUzInxE3okLL8iauXUBkNu0tHAz9W1VH9NowZB4KCuw8gIXrFdvWq98Iia6RFwS7RWMg8gVKibdicp/0",
			'email' => 'user'.'@gmail.com',
			'email_status' => 1,
			'subscribed' => 1,
			'password' => bcrypt('secret')
		]);


		DB::table('user')->insert([
			'openid' => "o89Rxt6-8Ckqv9I_mXiYWXoqhz2g",
			'username' => "教师",
			'gender' => 1,
			'permission_string' => '',
			'headimgurl' => "http://wx.qlogo.cn/mmopen/hDHfY6iauFKeb08Nu1BL1NvqUzInxE3okLL8iauXUBkNu0tHAz9W1VH9NowZB4KCuw8gIXrFdvWq98Iia6RFwS7RWMg8gVKibdicp/0",
			'email' => 'teacher'.'@gmail.com',
			'email_status' => 1,
			'subscribed' => 1,
			'password' => bcrypt('secret'),
			'certificate_as' => "TEACHER"
		]);
	}
}
