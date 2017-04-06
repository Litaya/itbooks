<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;

class AdminAdminController extends Controller
{

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
    public function getIndex(Request $request)
    {
        // TODO: refine users to display in index

        $sql_statement = "select user.id id, user.username username, admin.role role, department.id department_id, district.id district_id 
                          from admin left join user on admin.user_id = user.id
                                     left join department on admin.department_id = department.id
                                     left join district on admin.district_id = district.id ";

        if($request->search){
            $sql_statement .= "where (user.id like %$search% or user.username like %$search$ or district.name like %$search% or department.name like %$search%) ";
        }

        // role part
        if($request->role){
            $role_statement = " role = ".$request->role." ";

            if($role_statement != ""){
                if($request->search)
                    $sql_statement .= " and " . $role_statement . " ";
                else
                    $sql_statement .= " where " . $role_statement . " ";
            }
        }

        /** WARNING: UNSAFE CALL **/
        $user_list = DB::select($sql_statement)->get();

        return view("admin.user.index")->withUsers($user_list);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // no creation action
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // no creation action
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // TODO: check current user permission

        $user = self::get_admin_user($id);
        return view("admin.user.show")->withUser($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // TODO: check current user permission

        $user = self::get_admin_user($id);
        return view("admin.user.edit")->withUser($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // TODO: check current user permission
        // TODO: check current user's priority level is higher than User::find($id)


        $this->validate($request, [
            "role" => "required|in:SUPERADMIN,DEPTADMIN,EDITOR,REPRESENTATIVE",
        ]);

        $bNewAdmin = false;
        $admin = Admin::where('user_id','=',$id)->first();
        
        if($admin === null){
            $admin = new Admin;    
            $bNewAdmin = true;
        }

        $admin->role = $request->role;
        switch($request->role){
            case "SUPERADMIN": 
                {
                    if($bNewAdmin) $admin->save();
                    else $admin->update();
                }
                break;
            case "DEPTADMIN":
                {
                    $this->validate($request, ["dept_id" => "required"]);
                    $find_department = DB::select('select id from department where id = ?', [$request->dept_id])->get();
                    if(empty($find_department)){
                        return view("admin.user.edit")->withErrors(["不存在的部门"]);
                    }
                    $admin->department_id = $request->dept_id;
                    if($bNewAdmin) $admin->save();
                    else $admin->update();
                }
                break;
            case "EDITOR":
                {
                    $this->validate($request, ["dept_id" => "required"]);
                    $find_department = DB::select('select id from department where id = ?', [$request->dept_id])->get();
                    if(empty($find_department)){
                        return view("admin.user.edit")->withErrors(["不存在的部门"]);
                    }
                    $admin->department_id = $request->dept_id;
                    if($bNewAdmin) $admin->save();
                    else $admin->update();
                }
                break;
            case "REPR":
                {
                    $this->validate($request, ["district_id" => "required"]);
                    $find_district = DB::select('select id from district where id = ?', [$request->district_id])->get();
                    if(empty($find_district)){
                        return view("admin.user.edit")->withErrors(["错误的地区"]);
                    }
                    $admin->district_id = $request->district_id;
                    if($bNewAdmin) $admin->save();
                    else $admin->update();
                }
                break;
            default:
                return view("admin.user.edit")->withErrors(["不存在的角色名称"]);
        }

        return view("admin.user.index")->withUser($user);
    }

    /**
     * Remove the specified resource from storage.b
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    
    }
}
