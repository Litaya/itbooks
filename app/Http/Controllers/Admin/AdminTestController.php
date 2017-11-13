<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\UserInfo;
use Illuminate\Http\Request;

use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;


class AdminTestController extends Controller
{
	public function index(Request $request){
		return view('admin.test.file_upload');
	}

    public function storeExpressInfo(Request $request){
		$this->validate($request,[
			"express_file" => 'required'
		]);
		$file = $request->file('express_file');
		if($file->getClientOriginalExtension()!= 'xlsx'){
			Session::flash('notice_message',"文件格式错误，只能上传xlsx格式的文件");
			Session::flash('notice_status','danger');
			return redirect()->route('admin.bookreq.index');
		}
		$location = FileHelper::saveExpressFile($request->file('express_file'));
		Excel::load('storage/app/public/'.$location, function ($reader){
			$data   = $reader->all();
			$failed = [];
			if (sizeof($data) == 0){
				$message = "文件内容为空！";
				Session::flash('notice_message',$message);
				Session::flash('notice_status','warning');
			}else{
				foreach ($data as $row){
					if($row['快递单号']!=null){
						$book    = Book::where('isbn',$row['isbn'])->first();
						$users   = UserInfo::where('realname',$row['姓名'])->get();
						$userIds = [];
						foreach ($users as $user){
							array_push($userIds, $user->user_id);
						}
						$book_req = BookRequest::where('book_id',$book->id)->whereIn('user_id',$userIds)->first();
						if($book_req == null){
							array_push($failed, $row);
							continue;
						}
						$book_req->order_number = $row['快递单号'];
						$book_req->status = 1; //审核通过，已发送快递
						$book_req->save();
					}
				}
				$message = "一共处理".sizeof($data)."条记录，处理成功".(sizeof($data) - sizeof($failed))."条，处理失败".sizeof($failed)."条，无法处理的记录有:\n";
				foreach ($failed as $row){
					$message .= $row['姓名']."，isbn：".$row['isbn']."；";
				}
				Session::flash('notice_message',$message);
			}
		});
	    return redirect()->route('admin.bookreq.index');
    }
}
