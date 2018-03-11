<?php

namespace App\Helpers;

use App\Dao\UserDao;
use App\Models\BookRequest;
use App\Models\District;
use App\Models\User;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Excel;

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

	public static function exportAllBookRequest(){
		$time = time();
		DB::connection()->disableQueryLog();

		$block_size = 1000;
		$block_index = 1;

		while ($block_index){

			if ($block_index % $block_size == 0){
				echo $block_index % $block_size."\n";
			}

			$book_requests = DB::select('select book.isbn as isbn, book.name as bookname, book.price as bookprice,user.email as email,status, message, book_request.phone as bookreqphone, receiver, order_number, book_request.address as bookreqaddress, department.code as code, department.name as department_name, book_request.created_at as created_at from book_request left join book on book.id = book_request.book_id left join user on user.id = book_request.user_id left join department on department.id = book.department_id order by book_request.created_at DESC limit '.$block_size.' offset '.($block_size*($block_index-1)));
			if (count($book_requests) == 0)
				break;

			$filename = date("Y-m-d")."样书申请单_".$time."_".$block_index;
			$export = Excel::create($filename, function($excel) use ($book_requests){
				$excel->sheet("样书申请单", function($sheet) use ($book_requests){

					$sheet->setAutoSize(true);

					$sheet->row(1, ["书代号", "书名", "定价", "常用邮箱", "申请状态","收货地址",
						"收件人",'联系方式', "运单号",'部门代码','部门名称','教材使用情况','备注','申请时间']);

					foreach($book_requests as $book_request){
						$sheet->appendRow([
							$book_request["isbn"]." ",
							$book_request["bookname"],
							$book_request["bookprice"],
							$book_request['email'],
							$book_request['status'],
							$book_request['bookreqaddress'],
							$book_request['receiver'],
							$book_request['bookreqphone'],
							$book_request['order_number'],
							$book_request['code'],
							$book_request['department_name'],
							json_decode($book_request['message'],true)['book_plan'],
							json_decode($book_request['message'],true)['remarks'],
							$book_request['created_at']
						]);
					}

					$sheet->setColumnFormat(array(
						'A' => '@',
						'B' => '@',
						'C' => '0.00',
						'D' => '@',
						'E' => '@',
						'F' => '@',
						'G' => '0',
						'H' => '@',
						'I' => '@',
						'J' => '@',
						'K' => '@',
						'L' => '@',
						'M' => '@',
						'N' => '@'
					));
				});
			})->store('xlsx');

			$block_index++;

			if (count($book_requests) < $block_size){
				echo "共导出【".$block_index."】条记录";
				break;
			}
		}
	}

	/**
	 * @param $start string 如果为null, 则表示导出全部
	 * @param $end string 如果为null,则导出本年
	 */
	public static function exportSubscribedTeacherByTime($start=null, $end = null){
		if ($start == null){
			$start = '2000-01-01 00:00:00';
			$end   = date('Y-01-01 00:00:00',strtotime('next year'));
		}
		if ($end == null ){
			$end = date('Y-01-01 00:00:00',strtotime('next year'));
		}
		$users = User::whereDate('created_at','>', date('Y-m-d',strtotime($start)))->whereDate('created_at',' < ', date('Y-m-d',strtotime($end)))->where('certificate_as','=',"TEACHER")->get();
		if (count($users) == 0)
			return;

		$filename = date("Y-m-d H:i:s",time())."_教师信息";
		$export   = Excel::create($filename, function ($excel) use ($users){
			$excel->sheet('教师信息',function ($sheet) use ($users){
				$sheet->setAutoSize(true);

				$sheet->row(1, ["用户名","真实姓名","邮箱","申请余量","省","市","地址","学校名称","院系名称","职务",
					"职称","教授课程1","学生人数1","教授课程2","学生人数2","教授课程3","学生人数3"]);

				foreach($users as $user){
					$valid_book_requests = BookRequest::where('user_id',$user->id)
						->where('status',1)
						->whereRaw('created_at > '.date_timestamp_get(new \DateTime(date('Y-01-01 00:00:00',strtotime('this year')))))
						->whereRaw('created_at < '.date_timestamp_get(new \DateTime(date('Y-01-01 00:00:00',strtotime('next year')))))->get();
                    $cur_l = json_decode($user->json_content,true)['teacher']['book_limit'];
					$limit = count($valid_book_requests)+$cur_l;

					$userInfo = $user->userInfo;
					$ijson    = json_decode($userInfo->json_content);
					$province = District::where('id',$userInfo->province_id)->first();
					$city     = District::where('id',$userInfo->city_id)->first();

					$sheet->appendRow([
						$user->username,
						$userInfo->realname,
						$user->email,
						$limit,
						empty($province) ? "" : $province->name,
						empty($city)? "" : $city->name,
						empty($userInfo->address)?"":$userInfo->address,
						empty($userInfo->workplace)?"":$userInfo->workplace,
						empty($ijson->department)? "" : $ijson->department,
						empty($ijson->position)? "" : $ijson->position,
						empty($ijson->jobtitle)? "" : $ijson->jobtitle,
						empty($ijson->course_name_1)? "" : $ijson->course_name_1,
						empty($ijson->number_stud_1)? "" : $ijson->number_stud_1,
						empty($ijson->course_name_2)? "" : $ijson->course_name_2,
						empty($ijson->number_stud_2)? "" : $ijson->number_stud_2,
						empty($ijson->course_name_3)? "" : $ijson->course_name_3,
						empty($ijson->number_stud_3)? "" : $ijson->number_stud_3
					]);
				}

				$sheet->setColumnFormat(array(
					'A' => '@', 'B' => '@', 'C' => '@', 'D' => '@', 'E' => '@', 'F' => '@',
					'G' => '@', 'H' => '@', 'I' => '@', 'J' => '@', 'K' => '@', 'L' => '@',
					'M' => '@', 'N' => '@', 'O' => '@', 'P' => '@', 'Q' => '@',
				));
			});
		})->store('xlsx');
	}
}
