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
    public function index(Request $request){
	    $order_fbs = OrderFeedback::whereRaw('status > -1')->orderBy('created_at','desc')->paginate(20);
		return view('admin.order_fb.index', compact('order_fbs'));
    }
    public function show(Request $request, $id){
    	$order_fb = OrderFeedback::whereRaw('status > -1')->where('id',$id)->first();
    	return view('admin.order_fb.show',compact('order_fb'));
    }
    public function pass($id){
    	if (in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"])){
			$order_feedback  = OrderFeedback::where('id',$id)->first();
			$user = $order_feedback->user;

			// TODO 更改用户的样书申请额度
		    $result = UserDao::updateBookRequestLimit($user,1);
//		    if ($result['status'] == UserDao::$SUCCESS){
//				return redirect()->route('admin.order_fb.index');
//		    }else{
//		    	return redirect()->route('admin.order_fb.index')->withErrors([]);
//		    }
	    }else{
		    redirect()->back()->withErrors(["您没有处理此订购反馈的权限"]);
	    }
    }
    public function reject(){}
}
