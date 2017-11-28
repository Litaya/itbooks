<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 2017/11/21
 * Time: 下午10:42
 */

namespace App\Dao;

use App\Models\User;

class UserDao{

	public static $LIMIT           = 10;  // 样书申请上界
	public static $UNHANDLED       = 0;
	public static $SUCCESS         = 1;
	public static $FAIL_OUTOFLIMIT = 2;
	public static $UNHANDLED_MSG       = "未处理";
	public static $SUCCESS_MSG         = "处理成功";
	public static $FAIL_MSG            = "处理失败";
	public static $FAIL_OUTOFLIMIT_MSG = "申请书目变更超过临界值";


	/**
	 * 修改用户的样书申请数目
	 * @param User $user
	 * @param Integer $num
	 * @return array $result
	 */
	public static function updateBookRequestLimit(User $user, $num){
		$result = [
			"status"  => UserDao::$UNHANDLED,
			"message" => UserDao::$UNHANDLED_MSG
		];
		$user_json = $user->json_content;
		$user_json = json_decode($user_json,true);
		$limit     = $user_json['teacher']['book_limit'];
		if($limit + $num < 0 || $limit+$num > UserDao::$LIMIT){
			$result["status"]  = UserDao::$FAIL_OUTOFLIMIT;
			$result["message"] = UserDao::$FAIL_OUTOFLIMIT_MSG;
			return $result;
		}
		$user_json['teacher']['book_limit'] += $num ;
		$user->json_content = json_encode($user_json);
		$user->save();

		$result["status"]  = UserDao::$SUCCESS;
		$result["message"] = UserDao::$SUCCESS_MSG;
		return $result;
	}
}