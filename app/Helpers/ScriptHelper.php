<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\UserInfo;
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

		$block_size = 2;
		$block_index = 1;

		while ($block_index){

			if ($block_index % $block_size == 0){
				echo $block_index % $block_size."\n";
			}

			$book_requests = DB::select('select book.isbn as isbn, book.name as bookname, book.price as bookprice,user.email as email,status, message, book_request.phone as bookreqphone, receiver, order_number, book_request.address as bookreqaddress, department.code as code, department.name as department_name from book_request left join book on book.id = book_request.book_id left join user on user.id = book_request.user_id left join department on department.id = book.department_id limit '.$block_size.' offset '.($block_size*($block_index-1)));
			if (count($book_requests) == 0)
				break;

			$filename = date("Y-m-d")."样书申请单_".$time."_".$block_index;
			$export = Excel::create($filename, function($excel) use ($book_requests){
				$excel->sheet("样书申请单", function($sheet) use ($book_requests){

					$sheet->setAutoSize(true);

					$sheet->row(1, ["书代号", "书名", "定价", "常用邮箱", "申请状态","收货地址",
						"收件人",'联系方式', "运单号",'部门代码','部门名称','教材使用情况','备注']);

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
}