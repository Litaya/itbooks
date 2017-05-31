<?php

namespace App\Http\Controllers\Admin\Wechat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WechatMenuController extends Controller
{
    public function index(){
    	return view('admin.wechat.menu.index');
    }
}
