<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
	public function logout(){
		if(Auth::check())
			Auth::logout(Auth::user());
		return redirect()->route('admin.index');
	}
}
