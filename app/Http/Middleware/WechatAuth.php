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
                /* MODIFIED ON 2017-04-08: directly write admin.role to session */
                $admin = Admin::where('user_id', '=', $user->id)->get();
                if(count($admin) != 0) $request->session()->put('adminrole', $admin[0]->role);
                $request->session()->save();
                /* END MODIFICATION */
		    }
	    }
        return $next($request);
    }
}
