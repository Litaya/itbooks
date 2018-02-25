<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderFeedback;
use Illuminate\Http\Request;

class OrderFbAdminController extends Controller
{
    public function index(Request $request){
	    $order_fbs = OrderFeedback::whereRaw('status > -1')->orderBy('created_at','desc')->paginate(20);
		return view('admin.order_fb.index', compact('order_fbs'));
    }
    public function show(Request $request){}
    public function pass(){}
    public function reject(){}
}
