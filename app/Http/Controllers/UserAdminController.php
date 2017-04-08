<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserInfo;
use Session;

class UserAdminController extends Controller
{

    public function index(Request $request){

        $users = [];

        if($request->search){
            $search = $request->search;
            $users = User::with('userinfo')
                    ->where("username", "like", "%$search%")
                    ->orWhere("email", "like", "%$search%")
                    ->orWhere("user_info.realname", "like", "%$search%")->get();
        }
        else{
            $users = User::whereRaw("LENGTH(permission_string) = 0")->paginate(15);
        }

        return view("admin.user.index")->withUsers($users);

    }

    public function show($id){
        $full_info = UserInfoController::get_user_info($id);
        return view("admin.user.show")->withUser($full_info);
    }

    public function edit($id){
        // 没有指定管理员可以修改的用户信息
    }

    public function update($id){
        // 没有指定管理员可以修改的用户信息
    }

    // POST
    public function promote(Request $request){
        $id = $request->id;
        $user = User::find($id);
        
        if(!$user) return redirect()->route("admin.user.index")->withErrors(["用户不存在"]);
        
        $user->permission_string = "NEWADMIN";
        $user->update();
        $old_admin = Admin::where('user_id', '=', $id)->get();

        if(count($old_admin) == 0){
            $admin = new Admin;
            $admin->role = "NEWADMIN";
            $admin->user_id = $id;
            $admin->save();
        }
        else{
            $admin = $old_admin[0];
            $admin->role = "NEWADMIN";
            $admin->update();
        }

        Session::flash('success', "用户".$user->username."已被提升为管理员");

        return redirect()->route("admin.user.index");
    }

    public function destroy(Request $request, $id){
        User::delete($id);
        UserInfo::delete($id);
    }

    
}
