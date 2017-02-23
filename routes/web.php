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

Route::get('show', function(){
	return view('book_request.show');
});


Route::get('/', function () {
	return view('welcome',['message'=>'欢迎来到书圈!']);
});
Route::get('/errors',"PerdmissionController@user_permission_error")->name('errors.index');

Route::get('/home', 'HomeController@index');
Auth::routes();

/**
 * wechat routes
 */
Route::group(["prefix" => "wechat"], function(){
	Route::get("/","WechatController@index");
    Route::post("/","WechatController@server");
});

// admin dashboard routes:
Route::group(["prefix" => "admin",'middleware' => ['auth']], function(){

	Route::get('/',function (){
		return view('admin.index');
	})->name('admin.index');

	Route::get('/errors',"PermissionController@admin_permission_error")->name('admin.errors.index');

	Route::post('/logout','Admin\AdminAuthController@logout')->name('admin.logout');

	Route::get('bookreq', 'BookRequestAdminController@getIndex')->name('admin.bookreq.index');
	Route::get('bookreq/{id}', 'BookRequestAdminController@show')->name('admin.bookreq.show');
	Route::post('bookreq/pass/{id}', 'BookRequestAdminController@pass')->name('admin.bookreq.pass');

	/*
	 * user routes
	 */
	Route::group(['prefix'=>'user'],function (){
		Route::get('/', 'Admin\AdminUserController@index')->name("admin.user.index");
	});

	Route::group(['prefix'=>'department'],function (){
		Route::get('/','Admin\DepartmentController@index')->name('admin.department.index');
		Route::get('/{id}','Admin\DepartmentController@showDepartment')->name('admin.department.show');

		Route::post('/create','Admin\DepartmentController@createDepartment')->name('admin.department.create');
		Route::post('/{department_id}/update','Admin\DepartmentController@updateDepartment')->name('admin.department.update');
		Route::post('/{department_id}/office/delete','Admin\DepartmentController@deleteOffice')->name('admin.office.delete');
	});
});
