<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group(["prefix"=>'book'],function (){
	Route::post('/search/{search_string}','BookController@getBooksBySearch')->name('api.book.search');
	Route::post('/search_teaching/{search_string}','BookController@getTeachingMaterialsBySearch')->name('api.book.search_teaching');
});

Route::group(["prefix"=>"admin"], function(){
	Route::get('getalldepartments', 'AdminAdminController@getAllDepartments')->name('api.admin.get_all_departments');
	Route::get('getadminrolemapping', 'AdminAdminController@getAdminRoleMapping')->name('api.admin.get_admin_role_mapping');
	Route::get('getallprovinces', "DistrictController@getProvinces")->name('api.admin.get_all_provinces');
});

Route::group(["prefix"=>"category"], function(){
	Route::get('/all','CategoryController@getAll')->name('api.category.all');
	Route::get('/exist','CategoryController@cateExist')->name('api.category.exist');
});

Route::group(["prefix"=>'material'], function (){
	Route::get('/cate_materials',"MaterialController@getCateMaterials")->name("api.material.catematerials");
});

