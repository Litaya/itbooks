<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserInfo;
use Session;
use Auth;
use app\Libraries\PermissionManager as PM;

class UserAdminController extends Controller
{

    private static function Auth_role(){
        return Session::get('adminrole');
    }

    public function index(Request $request){

        $users = [];

        $adminrole = PM::getAdminRole();

        $scope_builder = User::nonAdmin();

        $bUserInfoJoined = false;

        if($request->search){
            $search = $request->search;
            $scope_builder = $scope_builder->join('user_info', 'user_info.user_id', '=', 'user.id')
                                            ->where("username", "like", "%$search%")
                                            ->orWhere("email", "like", "%$search%")
                                            ->orWhere("user_info.realname", "like", "%$search%");
            $bUserInfoJoined = true;
        }
        if($request->role && $request->role != "all"){
            if(!$bUserInfoJoined)
                $scope_builder = $scope_builder->join('user_info', 'user_info.user_id', '=', 'user.id');
            $scope_builder = $scope_builder->where('user_info.role', '=', $request->role);
            $bUserInfoJoined = true;
        }

        switch($adminrole){
            
            case "SUPERADMIN": 
                $users = $scope_builder->paginate(15);
                break;

            case "REPRESENTATIVE":
                if(!$bUserInfoJoined)
                    $scope_builder = $scope_builder->join('user_info', 'user_info.user_id', '=', 'user.id');
                $users = $scope_builder
                            ->where("user_info.province_id", "=", PM::getAdminDistrict())
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

    public function update($id, Request $request){
        
        if(!empty($request->role)){
            $user = User::find($id);
            switch($request->role){
                case "TEACHER":
                {
                    $user->certificate_as = "TEACHER";
                    if(empty($user->json_content)){
                        $jdata = [];
                        $jdata["teacher"] = [];
                        $jdata["teacher"]["book_limit"] = 10;
                        $user->json_content = json_encode($jdata);
                    } else {
                        $jdata = json_decode($user->json_content, true);
                        if(!array_key_exists("teacher", $jdata))
                            $jdata["teacher"] = [];
                        if(!array_key_exists("book_limit", $jdata["teacher"]))
                            $jdata["teacher"] = 10;
                        $user->json_content = json_encode($jdata);
                    }
                    $user->update();
                }
                break;

                case "STUDENT":
                {
                    $user->certificate_as = "STUDENT";
                    $user->update();
                }
                break;

                case "STAFF":
                {
                    $user->certificate_as = "STAFF";
                    $user->update();
                }
                break;

                case "OTHER":
                {
                    $user->certificate_as = "OTHER";
                    $user->update();
                }
                break;

                default:
                    break; // bad code
            }
        }

        if(!empty($request->book_limit)){
            $user = User::find($id);
            if($user->certificate_as == "TEACHER"){
                $limit = $request->book_limit;
                $jdata = json_decode($user->json_content, true);
                $jdata["teacher"]["book_limit"] = $limit;
                $user->json_content = json_encode($jdata);
                $user->update();
            }
        }

        Session::flash('success', '成功修改用户信息');
        return redirect()->route("admin.user.show", $id);
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
        DB::table('book_request')->where('user_id', '=', $id)->delete();
    }

    
}
