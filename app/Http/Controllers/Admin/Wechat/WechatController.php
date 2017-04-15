<?php

namespace App\Http\Controllers\Admin\Wechat;

use App\Http\Controllers\Controller;
use App\Models\WechatAutoReply;
use App\Models\WechatModuleModel;
use Illuminate\Http\Request;

class WechatController extends Controller
{
	/**
	 * 微信控制后台首页
	 */
    public function index(){
    	$wechat_modules    = WechatModuleModel::orderBy('weight','desc')->get();
    	$wechat_auto_reply = WechatAutoReply::all();
		return view('admin.wechat.index',compact('wechat_modules','wechat_auto_reply'));
    }

	/**
	 * 开启、关闭某一模块的功能
	 */
    public function changeModuleStatus(){
    }
}
