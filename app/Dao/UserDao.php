<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 2017/11/21
 * Time: 下午10:42
 */

namespace App\Dao;

use App\Models\BookRequest;
use App\Models\District;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Log;

class UserDao{

	public static $LIMIT           = 12;  // 样书申请上界
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
		$limit     = self::getUserYearLimit($user);
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

	public static function getAllTeachers(){
		$teachers = User::where('certificate_as','TEACHER')->get();
        Log::info(sizeof($teachers));   $count = 0;     
		$records  = array();
		foreach ($teachers as $teacher){
            $count++; 
            if($count % 100 == 0){
				Log::info($count);
            }
			$record = [
				'username'   => $teacher->username,
				'email'      => $teacher->email,
				'ujson'      => json_decode($teacher->json_content),
				'created_at' => $teacher->created_at
			];

			$user_info = UserInfo::where('user_id',$teacher->id)->first();
			if ($user_info == null) continue;
			$record['realname']  = $user_info->realname;
			$record['address']   = $user_info->address;
			$record['workspace'] = $user_info->school_name;
			$record['ijson']     = json_decode($user_info->json_content);

			$province = District::where('id',$user_info->province_id)->first();
			$city     = District::where('id',$user_info->city_id)->first();
			$record['province'] = empty($province) ? "" : $province->name;
			$record['city']     = empty($city)? "" : $city->name;

			array_push($records,$record);
		}
		return $records;
	}

	/**
	 * 本函数用于获取用户本年总共的申请额度
	 * @param User $user
	 * @return int
	 */
	public static function getUserYearLimit(User $user){
		$valid_book_requests = BookRequest::where('user_id',$user->id)
			->where('status',1)
			->whereDate('created_at','>',date('Y-01-01',strtotime('this year')))
			->whereDate('created_at','<',date('Y-01-01',strtotime('next year')))->get();
		return count($valid_book_requests)+$user->getBookLimit();
	}

}
