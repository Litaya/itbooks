<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function user_permission_error(){
    	return view('errors.index',['message'=>'对不起,&nbsp;您无权访问此页面','status'=>'danger']);
    }

	public function admin_permission_error(){
		return view('admin.errors.index',['message'=>'对不起,您无权访问此页面']);
	}
}
