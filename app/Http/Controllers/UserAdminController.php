<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserInfo;
use Session;
use Auth;
use App\Libraries\PermissionManager;

class UserAdminController extends Controller
{

    private static function Auth_role(){
        return Session::get('adminrole');
    }

    public function index(Request $request){

        $users = [];

        $admin = Admin::where('user_id', '=', Auth::id())->first();

        $scope_builder = User::nonAdmin();
        if($request->search){
            $search = $request->search;
            $scope_builder = $scope_builder->join('user_info', 'user_info.user_id', '=', 'user.id')
                                            ->where("username", "like", "%$search%")
                                            ->orWhere("email", "like", "%$search%")
                                            ->orWhere("user_info.realname", "like", "%$search%");
        }
        if($request->role && $request->role != "all"){
            $scope_builder = $scope_builder->join('user_info', 'user_info.user_id', '=', 'user.id')
                                            ->where('user_info.role', '=', $request->role);
        }

        switch($admin->role){
            case "SUPERADMIN": 
                $users = $scope_builder->paginate(15);
                break;
            case "REPRESENTATIVE":
                $users = $scope_builder
                            ->where("user_info.province_id", "=", $admin->district_id)
                            ->paginate(15);
                break;
            default: break; // NO PERMISSION
        }

        return view("admin.user.index")->withUsers($users);

    }

    public function show($id){
        $user = User::find($id);
        return view("admin.user.show")->withUser($user);
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