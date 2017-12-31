<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\UserInfo;

/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 2017/12/5
 * Time: 下午7:45
 */

class ScriptHelper{

	// 清空用户地址
	public function clearUserAddress(){
		$users   = UserInfo::all();
		$counter = 0;
		foreach ($users as $user){
			$counter ++;
			$user->address = null;
			$user->save();
			if($counter%500 == 0){
				echo "已处理 $counter 条消息";
			}
		}
		echo "处理完毕";
	}

	public static function resetBookLimit(){
		$users = User::where('certificate_as','like','%TEACHER%')->get();
		$counter = 0;
		foreach ($users as $user){
			$counter ++;
			if($counter % 100 == 0){
				echo "$counter\n";
			}
			$user_json = $user->json_content;
			$user_json = json_decode($user_json,true);
			$user_json['teacher']['book_limit'] = 10 ;
			$user->json_content = json_encode($user_json);
			$user->save();
		}
		echo "共处理 $counter 条记录";
	}
}