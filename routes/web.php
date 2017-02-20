<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
	return view('welcome',['message'=>'欢迎来到书圈!']);
//	return view('welcome',['message'=>"您还未设置登录密码,请尽快设置",'status'=>'danger','url'=>'/user/1/setPassword']);
});

Route::get('/admin',function (){
	return view('admin.index');
});

Route::post('/wechat','WechatController@index');