<?php

namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use App\Models\Book;
use App\Models\Department;
use App\Models\OrderFeedback;
use App\Models\Wechat;
use Illuminate\Http\Request;

use app\Libraries\PermissionManager as PM;

use Auth;
use Session;

class OrderFbController extends Controller
{
	// 订单反馈信息填写页
	public function index(){
	    $app      = Wechat::getInstance()->getApp();
	    $js       = $app->js;
    	return view('order_fb.index',compact('js'));
//		return view('order_fb.index');
	}

	public function records(){
		$order_fbs = OrderFeedback::whereRaw('status > -1')
            ->where('user_id',Auth::user()->id)
            ->whereDate('created_at','>',date('Y-01-01 00:00:00',strtotime('this year')))
            ->whereDate('created_at','<',date('Y-01-01 00:00:00',strtotime('next year')))
            ->orderBy('status')->orderBy('created_at','desc')->paginate(10);
		return view('order_fb.records',compact('order_fbs'));
	}

	public function show(Request $request, $id){
		$fb = OrderFeedback::find($id);
		return view('order_fb.show',compact('fb'));
	}

	public function submit(Request $request){
		$this->validate($request,[
			'isbn'           => 'required',
			'count'          => 'required',
			'image_media_id' => 'required',
			'order_datetime' => 'required'
		]);

		if($request->image_media_id){
			// 检查isbn的有效性
			$book = Book::where('isbn','like', "%".$request->isbn)->first();
			if ($book == null){
               return redirect()->route('order_fb.index')->withErrors(['图书isbn无效，您输入的isbn号为'.$request->isbn.'，请截图后联系管理员！']);   
			}
			$book_isbn = $book->isbn;
			$book_id   = $book->id;

            $user = Auth::user();
            $order_feedback_old = OrderFeedback::whereIn('status',[0,1])
                ->where('book_id',$book->id)->where('user_id',$user->id)
                ->whereDate('created_at','>',date('Y-01-01 00:00:00',strtotime('this year')))
                ->whereDate('created_at','<',date('Y-01-01 00:00:00',strtotime('next year')))->first();
            if (!empty($order_feedback_old)){
                return redirect()->route('order_fb.index')->withErrors(['您已在本年度申请过该书籍的订购反馈']);
            }

			// 获取department信息
			$department      = Department::where('id',$book->department_id)->first();
			$department_name = $department->name;
			$department_id   = $department->id;

			// 保存认证图片
			$app       = Wechat::getInstance()->getApp();
			$temporary = $app->material_temporary;
			$folder    = FileHelper::userCertificateFolder(Auth::user());
			$filename  = time();
			$filename  = $temporary->download($request->image_media_id, storage_path($folder), $filename);
			Session::flash("success", "信息保存成功");

			// 获取order信息
			$order_count = $request->count;
			$order_time  = $request->order_datetime;

			// 获取用户信息
			$user = Auth::user();
			$user_id = $user->id;
			$user_realname = $user->userinfo->realname;


			$order_fb = OrderFeedback::create([
				'book_id'         => $book_id,
				'book_isbn'       => $book_isbn,
				'department_id'   => $department_id,
				'department_name' => $department_name,
				'user_id'         => $user_id,
				'user_realname'   => $user_realname,
				'order_time'      => $order_time,
				'order_count'     => $order_count,
				'image_path'      => $folder.$filename,
				'status'          => 0
			]);

		}else{
			Session::flash("danger","图片上传失败，请联系管理员！");
			return redirect()->route('order_fb.index');
		}
		return redirect()->route('order_fb.records');
		// 创建订单记录
	}
	public function cancel(){}
	public function drop(){}
}
