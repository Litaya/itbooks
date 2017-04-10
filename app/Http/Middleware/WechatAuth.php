<?php

namespace App\Http\Middleware;

use App\Libraries\PermissionManager;
use App\Models\User;
use App\Models\Admin;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class WechatAuth
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
    	if($request->has('openid')){
    		$user = User::where(['openid'=>$request->openid,'subscribed'=>1])->first();
		    if (!empty($user) && !Auth::check()){
		    	Auth::login($user);
			    $request->session()->put('permission',PermissionManager::resolve($user->permission_string));
                
                /* 2017-04-08 新增的修改 */
                if(!empty($user->permission_string) and strlen($user->permission_string) > 0){
                    $admin = \App\Models\Admin::where('user_id', '=', $user->id)->first();
                    $request->session()->put("adminrole", $admin->role);
                    switch($admin->role){
                        case "DEPTADMIN":
                            $code = \App\Models\Department::find($admin->department_id)->code;
                            $request->session()->put("admindept", $admin->department_id);
                            $request->session()->put("admindeptcode", $code);
                            break;
                        case "REPRESENTATIVE":
                            $request->session()->put("admindist", $admin->district_id);
                            break;
                    }
                }
		    }
	    }
        return $next($request);
    }
}
