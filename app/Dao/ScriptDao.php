<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 2018/2/6
 * Time: 下午8:58
 */
namespace App\Dao;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ScriptDao{
	public static function exportAllTeachers(){
		$records  = UserDao::getAllTeachers();
		Log::info(sizeof($records));

		$filename = date("Y-m-d")."_教师信息_".time().".csv";
		$fullpath = "exports/".$filename;
		$t_header = "用户名,真实姓名,邮箱,申请余量,省,市,地址,学校名称,院系名称,职务,职称,教授课程1,学生人数1,教授课程2,学生人数2,教授课程3,学生人数3";
		Storage::put($fullpath,$t_header);
		$count = 0;
		foreach ($records as $record){
			$count ++;
			if ($count % 100 == 0){
				Log::info($count);
			}
			$ujson = $record["ujson"];
			$ijson = $record["ijson"];
			$username = !empty($record["username"]) ?   $record["username"] : "";
			$realname = !empty($record["realname"]) ?   $record["realname"] : "";
			$email    = !empty($record["email"]) ?      $record["email"] : "";
			$book_lim = empty($ujson->teacher) ? "" : (empty($ujson->teacher->book_limit) ? "" : $ujson->teacher->book_limit);
			$province = !empty($record["province"]) ?   $record["province"] : "";
			$city     = !empty($record["city"]) ?       $record["city"] : "";
			$address  = !empty($record["address"]) ?    $record["address"] : "";
			$workplace = !empty($record["workplace"]) ?  $record["workplace"] : "";
			$department = !empty($ijson->department) ?    $ijson->department : "";
			$position   = !empty($ijson->position) ?      $ijson->position : "";
			$jobtitle   = !empty($ijson->jobtitle) ?      $ijson->jobtitle : "";
			$course_name_1 = !empty($ijson->course_name_1) ? $ijson->course_name_1 : "";
			$number_stud_1 = !empty($ijson->number_stud_1) ? $ijson->number_stud_1 : "";
			$course_name_2 =!empty($ijson->course_name_2) ? $ijson->course_name_2 : "";
			$number_stud_2 = !empty($ijson->number_stud_2) ? $ijson->number_stud_2 : "";
			$course_name_3 = !empty($ijson->course_name_3) ? $ijson->course_name_3 : "";
			$number_stud_3 =!empty($ijson->number_stud_3) ? $ijson->number_stud_3 : "";
			$line = $username.",". $realname.",".
				$email.",".
				$book_lim.",".
				$province.",".
				$city.",".
				$address.",".
				$workplace.",".
				$department.",".
				$position.",".
				$jobtitle.",".
				$course_name_1.",".
				$number_stud_1.",".
				$course_name_2.",".
				$number_stud_2.",".
				$course_name_3.",".
				$number_stud_3;
			Storage::append($fullpath,$line);
		}
	}
}
