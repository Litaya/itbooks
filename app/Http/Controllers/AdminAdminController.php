<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Department;

use Session;
use DB;

class AdminAdminController extends Controller
{

    private static function Auth_role(){
        return Session::get('adminrole');
    }

    public static function get_admin_user($id){
        $user = User::find($id);
        $admin = Admin::where('user_id','=',$id);
        $user->role = $admin->role;
        $user->district_id = $admin->district_id;
        $user->department_id = $admin->department_id;

        return $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // TODO: refine users to display in index

        $user_list = [];

        $scope_builder = User::admin()->leftJoin('admin', 'admin.user_id', '=', 'user.id')
                                      ->leftJoin('user_info', 'user_info.user_id', '=', 'user.id')
                                      ->leftJoin('department', 'department.id', '=', 'admin.department_id')
                                      ->leftJoin('district', 'district.id', '=', 'admin.district_id');
                                      
        if($request->search){
            $search = $request->search;
            $scope_builder = $scope_builder->where("username", "like", "%$search%")
                                            ->orWhere("email", "like", "%$search%")
                                            ->orWhere("user_info.realname", "like", "%$search%");
        }
        if($request->role && $request->role != "all"){
            $scope_builder = $scope_builder->where('admin.role', '=', $request->role);
        }

        /** WARNING: UNSAFE CALL **/
        $user_list = $scope_builder->paginate(20, array('user.id as id', 'admin.role as role', 'department.name as dept_name', 'district.name as dist_name', 'user.email as email', 'user.username as username'));
        for($i=0;$i<count($user_list);$i++){
            $user_list[$i] = (object)$user_list[$i];
            switch($user_list[$i]->role){
                case "NEWADMIN": $user_list[$i]->role_translation = "未分配"; break;
                case "SUPERADMIN": $user_list[$i]->role_translation = "超级管理员"; break;
                case "DEPTADMIN": $user_list[$i]->role_translation = "部门管理员"; break;
                case "REPRESENTATIVE": $user_list[$i]->role_translation = "地区代表"; break;
            }
        }

        return view("admin.admin.index")->withUsers($user_list);
    }


    public function update(Request $request)
    {
        // TODO: check current user permission
        // TODO: check current user's priority level is higher than User::find($id)

        $id = $request->id;
        $user = User::find($id);

        $this->validate($request, [
            "id" => "required|integer",
            "role" => "required|in:SUPERADMIN,DEPTADMIN,EDITOR,REPRESENTATIVE",
        ]);

        $bNewAdmin = false;
        $old_admin = Admin::where('user_id','=',$id)->get();
        $admin = null;

        if(count($old_admin) == 0){
            $admin = new Admin;    
            $bNewAdmin = true;
        }
        else
            $admin = $old_admin[0];

        $admin->user_id = $id;
        $admin->role = $request->role;
        switch($request->role){
            case "SUPERADMIN": 
                {
                    if($bNewAdmin) $admin->save();
                    else $admin->update();

                    $user->permission_string = "all";
                    $user->update();
                }
                break;
            case "DEPTADMIN":
                {
                    $this->validate($request, ["dept_id" => "required"]);
                    $find_department = DB::select('select id from department where id = ?', [$request->dept_id]);
                    if(empty($find_department)){
                        return redirect()->back()->withErrors(["不存在的部门"]);
                    }
                    $admin->department_id = $request->dept_id;
                    if($bNewAdmin) $admin->save();
                    else $admin->update();

                    $user->permission_string = "BOOK_CURD_D0|DEPARTMENT_CURD_D0|USER_CURD_D0";
                    //$user->permission_string = "all";
                    $user->update();
                }
                break;
            case "EDITOR":
                {
                    $this->validate($request, ["dept_id" => "required"]);
                    $find_department = DB::select('select id from department where id = ?', [$request->dept_id]);
                    if(empty($find_department)){
                        return redirect()->back()->withErrors(["不存在的部门"]);
                    }
                    $admin->department_id = $request->dept_id;
                    if($bNewAdmin) $admin->save();
                    else $admin->update();

                    $user->permission_string = "BOOK_CURD_D0|DEPARTMENT_R_D0";
                    //$user->permission_string = "all";
                    $user->update();
                }
                break;
            case "REPRESENTATIVE":
                {
                    $this->validate($request, ["district_id" => "required"]);
                    $find_district = DB::select('select id from district where id = ?', [$request->district_id]);
                    if(empty($find_district)){
                        return redirect()->back()->withErrors(["错误的地区"]);
                    }
                    $admin->district_id = $request->district_id;
                    if($bNewAdmin) $admin->save();
                    else $admin->update();

                    $user->permission_string = "USER_R_P0";
                    //$user->permission_string = "all";
                    $user->update();
                }
                break;
            default:
                return redirect()->back()->withErrors(["不存在的角色名称"]);
        }

        Session::flash('success', '成功修改管理员角色');

        return redirect()->route('admin.admin.index');
    }

    /**
     * Remove the specified resource from storage.b
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // !! this function should never be called !!
    public function destroy($id)
    {
        // remove admin and user
        $user = User::find($id);
        $admin = Admin::where('user_id', '=', $id);

        $user->delete();
        $admin->delete();

        return redirect()->route("admin.admin.index");
    }


    public function demote(Request $request){
        $id = $request->id;
        
        $user = User::find($id);
        $user->permission_string = "";
        $user->update();

        $admin = Admin::where('user_id', '=', $id);
        $admin->delete();

        Session::flash('success', '成功取消了'.$user->username.'的管理员权限');
        return redirect()->route("admin.admin.index");
    }


    /** API: 获取所有部门 **/
    public function getAllDepartments(){
        $departments = Department::whereRaw('LENGTH(code) = 1')->get();
        $data = [];
        for($i = 0; $i < count($departments); $i++){
            $data[$departments[$i]->id] = $departments[$i]->name;
        }
        return response()->json($data, 200);
    }

    /** API: 获取管理员-角色关系表 **/
    public function getAdminRoleMapping(){
        $admin = Admin::all();
        $data = [];
        for($i = 0; $i < count($admin); $i++){
            $data[$admin[$i]->user_id] = $admin[$i]->role;
        }
        return response()->json($data, 200);
    }

    
}
