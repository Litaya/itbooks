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


Route::resource('resource', 'ResourceController');
Route::post('resource/{id}/download', 'ResourceController@postDownload')->name("resource.download"); // TODO: 增加支付积分逻辑，增加支付路由(getDownload)，编写下载逻辑

/* get image from storage */
Route::get('image/{src?}', function ($src){
    return Image::make(storage_path($src))->response();
})->where('src', '(.*)')->name('image');

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
	Route::get("{id}", "BookRequestController@show")->name("bookreq.show");
	Route::post("store", "BookRequestController@store")->name("bookreq.store");
	Route::delete("destroy", "BookRequestController@destroy")->name("bookreq.destroy"); // Somehow I made BookRequestAdminCtrler used this. 
																						// Should it be fixed?
	// Users do not have the access to edit/update an book request;
});

/* user certification module */
Route::resource('cert', 'CertificationController');


Route::get('/', function () {
	return view('welcome',['message'=>'欢迎来到书圈!']);
});
Route::get('/errors',"PermissionController@user_permission_error")->name('errors.index');

Route::get('/home', 'HomeController@index');
Auth::routes();

/**
 * wechat routes
 */
Route::group(["prefix" => "wechat"], function(){
	Route::get("/","WechatController@index");
    Route::post("/","WechatController@server");
});


Auth::routes();

Route::get('/home', 'HomeController@index');

// admin dashboard routes:
Route::group(["prefix" => "admin",'middleware' => ['auth']], function(){

	Route::get('/',function (){
		return view('admin.index');
	})->name('admin.index');
	Route::get('/errors',"PermissionController@admin_permission_error")->name('admin.errors.index');
	Route::post('/logout','Admin\AdminAuthController@logout')->name('admin.logout');

	Route::get('bookreq', 'BookRequestAdminController@getIndex')->name('admin.bookreq.index');
	Route::get('bookreq/{id}', 'BookRequestAdminController@show')->name('admin.bookreq.show');
	Route::post('bookreq/{id}/pass', 'BookRequestAdminController@pass')->name('admin.bookreq.pass');
	Route::post('bookreq/{id}/reject', 'BookRequestAdminController@reject')->name('admin.bookreq.reject');
	Route::delete('bookreq/{id}', 'BookRequestAdminController@destroy')->name('admin.bookreq.destroy');

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
	
	/*
	 * user routes
	 */
	Route::group(['prefix'=>'user'],function (){
		Route::get('/', 'Admin\AdminUserController@index')->name("admin.user.index");
	});

	Route::group(['prefix'=>'department'],function (){
		Route::get('/','Admin\DepartmentController@index')->name('admin.department.index');
		Route::get('/{department_id}','Admin\DepartmentController@showDepartment')->name('admin.department.show');

		Route::post('/create','Admin\DepartmentController@createDepartment')->name('admin.department.create');
		Route::post('/{department_id}/update','Admin\DepartmentController@updateDepartment')->name('admin.department.update');
		Route::post('/{department_id}/office/delete','Admin\DepartmentController@deleteOffice')->name('admin.office.delete');
	});
});

