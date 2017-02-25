<?php

namespace App\Http\Middleware;

use App\Libraries\PermissionManager;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Permission
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if(Auth::check()){
			$uri= $request->getRequestUri();
			$uri_arr = explode('/',$uri);

			if($uri_arr[1]=='admin' && Auth::user()->permission_string == "" ){
				return redirect('/');
			}elseif ($uri_arr[1]=='' && Auth::user()->permission_string != ""){
				return redirect('/admin');
			}

			if($uri_arr[1] == 'admin'){
				if(count($uri_arr) >= 3){
					if($uri_arr[2] == 'department'){
						if(isset($request->department_id)){
							if(!PermissionManager::hasDepartmentPermission($request,$request->department_id)){
								$request->session()->flash('notice_message','您没有该部门的权限');
								$request->session()->flash('notice_status','danger');
								return redirect()->route('admin.department.index');
							}
						}
					}
				}
			}
		}

		return $next($request);
	}
}
