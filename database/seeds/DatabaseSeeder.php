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
		/*
		 * 超级管理员，在表 user, admin, user_info 中均添加相关信息
		 */
		DB::table('user')->insert([
			'id'                => 1,
			'openid'            => "o89Rxt6-8Ckqv9I_mXiYWXoqhz3k",
			'username'          => "超级管理员",
			'gender'            => 2,
			'permission_string' => 'all',
			'headimgurl'        => "http://wx.qlogo.cn/mmopen/hDHfY6iauFKeb08Nu1BL1NvqUzInxE3okLL8iauXUBkNu0tHAz9W1VH9NowZB4KCuw8gIXrFdvWq98Iia6RFwS7RWMg8gVKibdicp/0",
			'email'             => 'super'.'@gmail.com',
			'email_status'      => 1,
			'subscribed'        => 1,
			'password'          => bcrypt('secret'),
			'source'            => 'web',
		]);

		DB::table('admin')->insert([
			'id'                => 1,
			'user_id'           => 1,
			'role'              => 'SUPERADMIN',
		]);

		DB::table('user_info')->insert([
			'user_id'=>1
		]);

		/*
		 * 部门管理员 在表user,admin,user_info中均添加信息
		 */
		DB::table('user')->insert([
			'id'                =>3,
			'openid'            => "o89Rxt6-8Ckqv9I_mXiYWXoqhz1k",
			'username'          => "部门管理员#1",
			'gender'            => 1,
			'permission_string' => 'book_curd_d1|bookreq_curd_d1',
			'headimgurl'        => "http://wx.qlogo.cn/mmopen/hDHfY6iauFKeb08Nu1BL1NvqUzInxE3okLL8iauXUBkNu0tHAz9W1VH9NowZB4KCuw8gIXrFdvWq98Iia6RFwS7RWMg8gVKibdicp/0",
			'email'             => 'department'.'@gmail.com',
			'email_status'      => 1,
			'subscribed'        => 1,
			'password'          => bcrypt('secret'),
			'source'            => 'web',
		]);
		DB::table('admin')->insert([
			'id'                => 3,
			'user_id'           => 3,
			'role'              => 'DEPARTMENTADMIN',
			'department_id'     => 1
		]);
		DB::table('user_info')->insert([
			'user_id'=>3
		]);

		/*
		 * 院校代表 user admin user_info
		 */
		DB::table('user')->insert([
			'id'                =>4,
			'openid'            => "o89Rxt6-8Ckqv9I_mXiYWXoqhz1k",
			'username'          => "院校代表#1",
			'gender'            => 1,
			'permission_string' => 'book_r_p1|bookreq_r_p1',
			'headimgurl'        => "http://wx.qlogo.cn/mmopen/hDHfY6iauFKeb08Nu1BL1NvqUzInxE3okLL8iauXUBkNu0tHAz9W1VH9NowZB4KCuw8gIXrFdvWq98Iia6RFwS7RWMg8gVKibdicp/0",
			'email'             => 'representative'.'@gmail.com',
			'email_status'      => 1,
			'subscribed'        => 1,
			'password'          => bcrypt('secret'),
			'source'            => 'web',
		]);
		DB::table('admin')->insert([
			'id'                => 4,
			'user_id'           => 4,
			'role'              => 'REPRESENTATIVE',
			'district_id'       => 1
		]);
		DB::table('user_info')->insert([
			'user_id'=>4
		]);

		/*
		 * 普通用户 user user_info
		 */
		DB::table('user')->insert([
			'id'                => 5,
			'openid'            => "o89Rxt6-8Ckqv9I_mXiYWXoqhz2k",
			'username'          => "普通用户",
			'gender'            => 2,
			'permission_string' => '',
			'headimgurl'        => "http://wx.qlogo.cn/mmopen/hDHfY6iauFKeb08Nu1BL1NvqUzInxE3okLL8iauXUBkNu0tHAz9W1VH9NowZB4KCuw8gIXrFdvWq98Iia6RFwS7RWMg8gVKibdicp/0",
			'email'             => 'user'.'@gmail.com',
			'email_status'      => 1,
			'subscribed'        => 1,
			'password'          => bcrypt('secret'),
			'source'            => 'web',
		]);
		DB::table('user_info')->insert([
			'user_id'=>5
		]);

		DB::table('user')->insert([
			'id'                => 6,
			'openid'            => "o89Rxt6-8Ckqv9I_mXiYWXoqhz2g",
			'username'          => "教师",
			'gender'            => 1,
			'permission_string' => '',
			'headimgurl'        => "http://wx.qlogo.cn/mmopen/hDHfY6iauFKeb08Nu1BL1NvqUzInxE3okLL8iauXUBkNu0tHAz9W1VH9NowZB4KCuw8gIXrFdvWq98Iia6RFwS7RWMg8gVKibdicp/0",
			'email'             => 'teacher'.'@gmail.com',
			'email_status'      => 1,
			'subscribed'        => 1,
			'password'          => bcrypt('secret'),
			'certificate_as'    => "TEACHER",
			'json_content'      => '{"teacher":{"book_limit":10}}',
			'source'            => 'web',
		]);
		DB::table('user_info')->insert([
			'user_id'           => 6,
			'phone'             => '13070123726',
			'qq'                => '1369918998',
			'school_id'         => 6,
			'school_name'       => '北京航空航天大学',
			'school_division'   => '软件学院',
			'school_title'      => '教授'
		]);
	}
}
