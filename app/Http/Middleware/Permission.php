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
		$uri= $request->getRequestUri();
        $uri_arr = explode('/',$uri);
		if($uri_arr[1]=='image'||$uri_arr[1] == 'mail' || $uri_arr[1]=='message'){
			return $next($request);
		}
		if(Auth::check()){
			$user = Auth::user();
			if($uri_arr[1]=='admin' ){
				if($user->permission_string == "" )
					return redirect('/');
			}elseif ($user->permission_string != ""){
				return redirect('/admin');
			}

			if($uri_arr[1] == 'admin'){
				if(count($uri_arr) >= 3){
					if (!PermissionManager::hasPermission($uri_arr[2])) {
						$request->session()->flash('notice_message', '对不起, 您没有此模块的权限');
						$request->session()->flash('notice_status', 'danger');
						return redirect()->route('admin.index');
					}
					switch ($uri_arr[2]) {
						case 'department':
							if (isset($request->department_id)) {
								if (!PermissionManager::hasPermission('department', "r", $request->department_id)) {
									$request->session()->flash('notice_message', '您没有该部门的权限');
									$request->session()->flash('notice_status', 'danger');
									return redirect()->route('admin.department.index');
								}
							}
							break;
						default:
							break;
					}
				}
			}else 
			{
				// if(strpos($uri_arr[1],'register') === false and empty($user->email)){
				// 	return redirect()->route('register.provision');
				// }
				// else if(strpos($uri_arr[1], 'register') !== false and !empty($user->email)){
				// 	return redirect()->route('index');
				// }
				if(strpos($uri_arr[1],'bookreq')!==false){
					$certificate_as = $user->certificate_as;
					$certification = explode('|',$certificate_as)[0];
					if($certification != 'TEACHER'){
						$request->session()->flash('notice_message', '申请样书需先认证教师身份');
						$request->session()->flash('notice_status', 'danger');
						return redirect()->route('cert.create');
					}
				}
			}
			
		}

		return $next($request);
	}
}
