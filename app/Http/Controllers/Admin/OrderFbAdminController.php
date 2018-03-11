<?php

namespace App\Http\Controllers\Admin;

use App\Dao\UserDao;
use App\Http\Controllers\Controller;
use App\Models\OrderFeedback;
use App\Models\User;
use Illuminate\Http\Request;

use App\Libraries\PermissionManager as PM;

class OrderFbAdminController extends Controller
{
	public function index(Request $request)
	{
		$order_fbs = OrderFeedback::whereRaw('status > -1')->orderBy('created_at', 'desc')->paginate(20);
		return view('admin.order_fb.index', compact('order_fbs'));
	}

	public function show(Request $request, $id)
	{
		$order_fb = OrderFeedback::whereRaw('status > -1')->where('id', $id)->first();
		return view('admin.order_fb.show', compact('order_fb'));
	}

	public function pass($id)
	{
		if (in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"])) {
			$order_feedback = OrderFeedback::where('id', $id)->first();
			$user = $order_feedback->user;

			// 检查同一书号本年是否已经上传并同意一次
			$order_feedback_old = OrderFeedback::where('status',1)
				->where('book_id','')
				->whereDate('created_at','>',date('Y-01-01',strtotime($order_feedback->created_at)))
				->whereDate('created_at','<',date('Y-01-01',strtotime(date('Y-01-01',strtotime($order_feedback->created_at)))+365*24*60*60))->first();
			if (!empty($order_feedback_old)){
				return redirect()->back()->withErrors(["用户本年已对本书籍申请一次订购证明"]);
			}

			// 更改用户的样书申请额度
			$result = UserDao::updateBookRequestLimit($user, 1);
			$order_feedback->status = 1;
			$order_feedback->save();

			if ($result['status'] == UserDao::$SUCCESS){
				return redirect()->back();
			}else{
				return redirect()->back()->withErrors($result['message']);
			}
		} else {
			return redirect()->back()->withErrors(["您没有处理此订购反馈的权限"]);
		}
	}

	public function reset($id){
		$order_feedback = OrderFeedback::where('id', $id)->first();

		// <0 是用户主动操作的结果，管理员不可改变其状态
		if (in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"]) || $order_feedback->status < 0) {
			if ($order_feedback->status == 1){
				UserDao::updateBookRequestLimit($order_feedback->user, -1);
			}
			if ($order_feedback->status == 2){
				$order_feedback->refuse_message = "";
			}
			$order_feedback->status = 0;
			$order_feedback->save();
			return redirect()->back();
		}else {
			return redirect()->back()->withErrors(["您没有处理此订购反馈的权限"]);
		}
	}

	public function reject(Request $request, $id)
	{
		if (in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"])) {
			$message  = $request->get('message');
			$order_fb = OrderFeedback::where('id',$id)->first();
			if (empty($order_fb)){
				return redirect()->back()->withErrors(["【".$id."】没有查询到相关记录，请截图并联系管理员"]);
			}
			$order_fb->refuse_message = $message;
			$order_fb->status         = 2;
			$order_fb->save();

			$request->session()->flash('notice_message','处理成功！');
			$request->session()->flash('notice_status','success');

			$args = [];
			if(!empty($request->page)) $args["page"] = $request->page;
			return redirect()->back()->withArgs($args);
		} else {
			return redirect()->back()->withErrors(["您没有处理此订购反馈的权限"]);
		}
	}
}