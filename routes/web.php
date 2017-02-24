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

/* get image from storage */
Route::get('image/{src?}', function ($src){
    return Image::make(storage_path($src))->response();
})->where('src', '(.*)');

/* book module for users */
Route::group(["prefix"=>"book"], function(){
	Route::get("/", "BookController@index")->name("book.index");
	Route::get("show/{id}", "BookController@show")->name("book.show");
});

/* book request module for users*/
//Route::resource('bookreq', 'BookRequestController');
Route::group(["prefix"=>"bookreq"], function(){
	Route::get("/", "BookRequestController@index")->name("bookreq.index");
	Route::get("create/{book_id}", "BookRequestController@create")->name("bookreq.create");
	Route::post("store", "BookRequestController@store")->name("bookreq.store");
	Route::delete("destroy", "BookRequestController@destroy")->name("bookreq.destroy"); // Somehow I made BookRequestAdminCtrler used this. 
																						// Should it be fixed?
	// Users do not have the access to edit/update an book request;
});

/* user certification module */
Route::resource('cert', 'CertificationController');


Route::get('/', function () {
	return view('welcome',['message'=>'欢迎来到书圈!']);
//	return view('welcome',['message'=>"您还未设置登录密码,请尽快设置",'status'=>'danger','url'=>'/user/1/setPassword']);
});

Route::get('/admin',function (){
	return view('admin.index');
});

Route::group(["prefix" => "wechat"], function(){
	Route::get("/","WechatController@index");
    Route::post("/","WechatController@server");
    Route::get('/test','WechatController@test');
});


Auth::routes();

Route::get('/home', 'HomeController@index');

// admin dashboard routes:
Route::group(["prefix" => "admin"], function(){
	Route::get('bookreq', 'BookRequestAdminController@getIndex')->name('admin.bookreq.index');
	Route::get('bookreq/{id}', 'BookRequestAdminController@show')->name('admin.bookreq.show');
	Route::post('bookreq/pass/{id}', 'BookRequestAdminController@pass')->name('admin.bookreq.pass');

	Route::get('cert', 'CertificationAdminController@index')->name('admin.cert.index');
	Route::get('cert/{id}', 'CertificationAdminController@show')->name('admin.cert.show');
	Route::post('cert/{id}/pass', 'CertificationAdminController@pass')->name('admin.cert.pass');
	Route::post('cert/{id}/reject', 'CertificationAdminController@reject')->name('admin.cert.reject');
	
	Route::get('book', 'BookAdminController@index')->name('admin.book.index');
	Route::post('book', 'BookAdminController@store')->name('admin.book.store');
	Route::get('book/create', 'BookAdminController@create')->name('admin.book.create');
	Route::get('book/{id}', 'BookAdminController@show')->name('admin.book.show');
	Route::put('book/{id}', 'BookAdminController@update')->name('admin.book.update');
	Route::delete('book/{id}', 'BookAdminController@destroy')->name('admin.book.destroy');
	Route::get('book/{id}/edit', 'BookAdminController@edit')->name('admin.book.edit');
	
});

