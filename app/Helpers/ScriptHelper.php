<?php

namespace App\Helpers;

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
}