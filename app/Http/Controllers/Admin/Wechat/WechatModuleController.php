<?php

namespace App\Http\Controllers\Admin\Wechat;

use App\Http\Controllers\Controller;
use App\Models\WechatModuleModel;
use Illuminate\Http\Request;

class WechatModuleController extends Controller
{
    public function index(){}

    public function changeModuleStatus(Request $request){
    	$this->validate($request,[
    		'module_id'     => 'required',
		    'module_status' => 'required'
	    ]);
    	WechatModuleModel::where('id',$request->get('module_id'))->update(['status'=>$request->get('module_status')]);
    	$request->session()->flash('wechat_message','操作成功!');
    	$request->session()->flash('wechat_status','success');
    	return 'success';
    }
}
