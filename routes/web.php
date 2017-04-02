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

Route::get('navigate', "NavigationController@navigate")->name('navigate');


Route::get('like', "LikeController@like")->name('like');
Route::get('unlike', "LikeController@unlike")->name('unlike');
Route::get('read', "ReadController@read")->name('read');
Route::get('unread', "ReadController@unread")->name('unread');

Route::get('register/provision', "FirstVisitController@getProvision")->name("register.provision");
Route::get('register/basic', "FirstVisitController@getBasic")->name("register.basic");
Route::post('register/basic/save', "FirstVisitController@postSaveBasic")->name("register.basic.save");
Route::get('register/teacher', "FirstVisitController@getTeacher")->name("register.teacher");
Route::post('register/teacher/save', "FirstVisitController@postSaveTeacher")->name("register.teacher.save");
Route::get('register/welcome', "FirstVisitController@getWelcome")->name("register.welcome");

Route::group(['prefix'=>'userinfo'], function(){
	Route::get('/',function (){
		return redirect()->route('userinfo.basic');
	})->name("userinfo.index");
	Route::get("basic", "UserInfoController@getBasic")->name("userinfo.basic");
	Route::get("detail", "UserInfoController@getDetail")->name("userinfo.detail");
	Route::get("teacher", "UserInfoController@getTeacher")->name("userinfo.teacher");
	Route::get("author", "UserInfoController@getAuthor")->name("userinfo.author");
	Route::get("missing", "UserInfoController@getMissing")->name("userinfo.missing");
	Route::post("basic", "UserInfoController@postSaveBasic")->name("userinfo.basic.save");
	Route::post("detail", "UserInfoController@postSaveDetail")->name("userinfo.detail.save");
	Route::post("teacher", "UserInfoController@postSaveTeacher")->name("userinfo.teacher.save");
	Route::post("author", "UserInfoController@postSaveAuthor")->name("userinfo.author.save");
	Route::post("missing", "UserInfoController@postSaveMissing")->name("userinfo.missing.save");

});


Route::group(['prefix'=>'conference'], function(){
	Route::get('/', 'ConferenceController@index')->name('conference.index');
	Route::get('{id}', 'ConferenceController@show')->name('conference.show');
	Route::post('{id}/register', 'ConferenceController@postRegister')->name('conference.register');
	Route::post('{id}/cancel', 'ConferenceController@postCancel')->name('conference.cancel');
});


Route::resource('resource', 'ResourceController');
Route::post('resource/{id}/download', 'ResourceController@postDownload')->name("resource.download.save"); // TODO: 增加支付积分逻辑，增加支付路由(getDownload)，编写下载逻辑

/* get image from storage */
Route::get('image/{src?}', function ($src){
	return Image::make(storage_path($src))->response();
})->where('src', '(.*)')->name('image');

/* book module for users */
Route::group(["prefix"=>"book"], function(){
	Route::get("/", "BookController@index")->name("book.index");
	Route::get("{id}", "BookController@show")->name("book.show");
	Route::get('{id}/updatekj', 'BookController@updateKejian')->name('book.updatekj');
	// Route::get('search', 'BookController@search')->name('book.search');
});

/* book request module for users*/
//Route::resource('bookreq', 'BookRequestController');
Route::group(["prefix"=>"bookreq"], function(){
	Route::get("/","BookRequestController@index")->name("bookreq.index");
	Route::post('store/multiple',"BookRequestController@storeMultiple")->name('bookreq.store.multiple');
	Route::get("/record", "BookRequestController@record")->name("bookreq.record");
	Route::get("create/{book_id}", "BookRequestController@create")->name("bookreq.create");
	Route::get("{id}", "BookRequestController@show")->name("bookreq.show");
	Route::post("store", "BookRequestController@store")->name("bookreq.store");
	Route::delete("/{id}/destroy", "BookRequestController@destroy")->name("bookreq.destroy");
	// Users do not have the access to edit/update an book request;
});

/* user certification module */
Route::resource('cert', 'CertificationController');


Route::get('/', function () {
	return redirect()->route('home');
})->name('index');
Route::get('/errors',"PermissionController@user_permission_error")->name('errors.index');

Route::get('/home', 'HomeController@index')->name('home');
Auth::routes();

/**
 * wechat routes
 */
Route::group(["prefix" => "wechat"], function(){
	Route::get("/",'Wechat\WechatController@index');
	Route::post("/",'Wechat\WechatController@server');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

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

	Route::get('cert', 'CertRequestAdminController@index')->name('admin.cert.index');
	//Route::get('cert/{id}', 'CertificationAdminController@show')->name('admin.cert.show');
	Route::get('cert/{id}', "CertRequestAdminController@show")->name('admin.cert.show');
	Route::post('cert/{id}/pass', 'CertRequestAdminController@pass')->name('admin.cert.pass');
	Route::post('cert/{id}/reject', 'CertRequestAdminController@reject')->name('admin.cert.reject');
	Route::post('cert/{id}/deprive', 'CertRequestAdminController@deprive')->name('admin.cert.deprive');
	Route::delete('cert/{id}', 'CertRequestAdminController@destroy')->name('admin.cert.destroy');

	Route::group(['prefix'=>'book'], function(){
		Route::get('/', 'BookAdminController@index')->name('admin.book.index');
		Route::post('/', 'BookAdminController@store')->name('admin.book.store');
		Route::get('create', 'BookAdminController@create')->name('admin.book.create');
		Route::get('{id}', 'BookAdminController@show')->name('admin.book.show');
		Route::put('{id}', 'BookAdminController@update')->name('admin.book.update');
		Route::delete('{id}', 'BookAdminController@destroy')->name('admin.book.destroy');
		Route::get('{id}/edit', 'BookAdminController@edit')->name('admin.book.edit');
		Route::get('import', 'DatabaseController@importBooks')->name('admin.book.import');
		Route::get('{id}/updatekj', 'BookAdminController@updateKejian')->name('admin.book.updatekj');
	});

	Route::group(['prefix'=>'resource'], function(){
		Route::get('/', 'ResourceAdminController@index')->name('admin.resource.index');
		Route::post('/', 'ResourceAdminController@store')->name('admin.resource.store');
		Route::get('create', 'ResourceAdminController@create')->name('admin.resource.create');
		Route::get('{id}', 'ResourceAdminController@show')->name('admin.resource.show');
		Route::put('{id}', 'ResourceAdminController@update')->name('admin.resource.update');
		Route::delete('{id}', 'ResourceAdminController@destroy')->name('admin.resource.destroy');
		Route::get('{id}/edit', 'ResourceAdminController@edit')->name('admin.resource.edit');
		Route::post('{id}/download', 'ResourceAdminController@postDownload')->name('admin.resource.download');
	});

	/*
	 * user routes
	 */
	Route::group(['prefix'=>'user'],function (){
		Route::get('/', 'Admin\AdminUserController@index')->name("admin.user.index");

		Route::post('/create','Admin\AdminUserController@create')->name('admin.user.create');
	});

	Route::group(['prefix'=>'department'],function (){
		Route::get('/','Admin\DepartmentController@index')->name('admin.department.index');
		Route::get('/{department_code}','Admin\DepartmentController@showDepartment')->name('admin.department.show');

		Route::post('/create','Admin\DepartmentController@createDepartment')->name('admin.department.create');
		Route::post('/{department_code}/update','Admin\DepartmentController@updateDepartment')->name('admin.department.update');
		Route::post('/{department_code}/office/delete','Admin\DepartmentController@deleteOffice')->name('admin.office.delete');
	});

	Route::group(['prefix'=>'conference'], function(){
		Route::get('/', 'ConferenceAdminController@index')->name('admin.conference.index');
		Route::post('/', 'ConferenceAdminController@store')->name('admin.conference.store');
		Route::get('create', 'ConferenceAdminController@create')->name('admin.conference.create');
		Route::get('{id}', 'ConferenceAdminController@show')->name('admin.conference.show');
		Route::put('{id}', 'ConferenceAdminController@update')->name('admin.conference.update');
		Route::delete('{id}', 'ConferenceAdminController@destroy')->name('admin.conference.destroy');
		Route::get('{id}/edit', 'ConferenceAdminController@edit')->name('admin.conference.edit');
		Route::get('{id}/export', "DatabaseController@exportConferenceRegisters")->name('admin.conference.export');
	});

	Route::group(['prefix'=>'material'], function() {
		Route::get('/','Wechat\WechatMaterialAdminController@index')->name('admin.material.index');
		Route::get('/{id}','Wechat\WechatMaterialAdminController@show')->name('admin.material.show');
		Route::post('/sync','Wechat\WechatMaterialAdminController@sync')->name('admin.material.sync');
	});
}); // end admin

Route::group(['prefix'=>'user','middleware' => ['auth']],function (){
	Route::get('/',"UserController@index")->name('user.index');
	Route::get('/teacher',"UserController@teacher")->name('user.teacher.index');
	Route::get('/email',"UserController@email")->name('user.email');
	Route::post('/email/store','UserController@storeEmail')->name('user.email.store');
	Route::get('/email/send_cert',"UserController@sendEmailCert")->name('user.email.send_cert');
	Route::get('/address','UserController@address')->name('user.address.index');
});

Route::get('test', function(){
	return view('test');
});

Route::group(["prefix" => "email"],function (){
	Route::get('/send','MailController@send');
	Route::get('/certificate/{token}',"MailController@certificate")->name('email.certificate');
});

Route::group(["prefix" => "message"],function (){
	Route::get('/',"MessageController@index")->name('message.index');
});
