<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(){
    	$users = User::paginate(10);
	    $admins = Admin::paginate(10);
		return view('admin.user.index',compact('users','admins'));
    }

	public function create(){

	}
}
