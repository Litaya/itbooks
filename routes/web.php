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

Route::resource('bookreq', 'BookRequestController');
Route::resource('cert', 'CertificationController');


Route::get('show', function(){
	return view('book_request.show');
});


Route::get('/', function () {
	return view('welcome',['message'=>'欢迎来到书圈!']);
//	return view('welcome',['message'=>"您还未设置登录密码,请尽快设置",'status'=>'danger','url'=>'/user/1/setPassword']);
});

Route::get('/admin',function (){
	return view('admin.index');
});

Route::post('/wechat','WechatController@index');


Auth::routes();

Route::get('/home', 'HomeController@index');

// admin dashboard routes:
Route::group(["prefix" => "admin"], function(){
	Route::get('bookreq', 'BookRequestAdminController@getIndex')->name('admin.bookreq.index');
	Route::get('bookreq/{id}', 'BookRequestAdminController@show')->name('admin.bookreq.show');
	Route::post('bookreq/pass/{id}', 'BookRequestAdminController@pass')->name('admin.bookreq.pass');

	Route::get('cert', 'CertificationAdminController@index')->name('admin.cert.index');
	Route::get('cert/{id}', 'CertificationAdminController@show')->name('admin.cert.show');
	Route::post('cert/pass/{id}', 'CertificationAdminController@pass')->name('admin.cert.pass');
	Route::post('cert/reject/{id}', 'CertificationAdminController@reject')->name('admin.cert.reject');
	
});

