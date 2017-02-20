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

Route::get('frontend', function(){
	return view('book_request.index');
});

Route::get('show', function(){
	return view('book_request.show');
});


Route::get('/', function () {
	return view('welcome',['message'=>'欢迎来到书圈']);
});

Route::get('/admin',function (){
	return view('admin.index');
});

Route::post('/wechat','WechatController@index');