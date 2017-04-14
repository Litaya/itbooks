<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Log;
use Session;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\CertRequest;
use App\Helpers\FileHelper;


class FirstVisitController extends Controller
{
    public function getProvision(){
        return view("register.provision");
    }

    public function getBasic(){
        return view("register.basic");
    }

    public function postSaveBasic(Request $request){
        // save basic
        Session::flash("last-picked-role", $request->role);
        

        if($request->role == "teacher") {
            $this->validate($request, [
                "realname" => "required",
                "email" => "required|email|unique:user,email",
                "qqnumber" => "digits_between:5,14",
                "phone" => "digits_between:11,15",
                "workplace" => "required",
                "department" => "required",
                "jobtitle" => "required",
                "course_name_1" =>"required",
                "number_stud_1" =>"required",
            ]);
            
            $user = Auth::user();
            $info = UserInfoController::get_user_info($user);
            $jdata = empty($info->json_content) ? [] : json_decode($info->json_content, true);

            $info->role = $request->role;
            $info->realname = $request->realname;
            $info->email = $request->email;
            $jdata['qqnumber'] = $request->qqnumber;
            $info->phone = $request->phone;
            $info->workplace = $request->workplace;
            $jdata['department'] = $request->department;
            $jdata['jobtitle'] = $request->jobtitle;
            $jdata['position'] = $request->position;
            $jdata['course_name_1'] = $request->course_name_1;
            $jdata['number_stud_1'] = $request->number_stud_1;
            $info->province_id = $request->province;
            if($info->province_id == "") $info->province_id = null;
            $info->city_id = $request->city;
            if($info->city_id == "") $info->city_id = null;
            
            $info->json_content = json_encode($jdata);
            UserInfoController::update_user_info($info);

            return redirect()->route("register.teacher");
        }
        
        
        if($request->role == "student"){
            $this->validate($request, [
                "realname" => "required",
                "email" => "required|email|unique:user,email",
                "school" => "required",
                "department" => "required",
            ]);

            $user = Auth::user();
            $info = UserInfoController::get_user_info($user);
            $jdata = empty($info->json_content) ? [] : json_decode($info->json_content, true);


            $info->role = $request->role;
            $info->email = $request->email;
            $info->realname = $request->realname;
            $info->json_content = json_encode($jdata);
            $jdata['school'] = $request->school;
            $jdata['department'] = $request->department;

            UserInfoController::update_user_info($info);

            return view("register.welcome")->withRole("student");
        }
        
        
        if($request->role == "other"){
            $this->validate($request, [
                "realname" => "required",
                "email" => "required|email|unique:user,email"
            ]);

            $user = Auth::user();
            $info = UserInfoController::get_user_info($user);
            $jdata = empty($info->json_content) ? [] : json_decode($info->json_content, true);

            $info->role = $request->role;
            $info->email = $request->email;
            $info->realname = $request->realname;
            $info->workplace = $request->workplace;
            $info->jobtitle = $request->jobtitle;
            $info->json_content = json_encode($jdata);

            UserInfoController::update_user_info($info);

            return redirect()->route("register.welcome")->withRole("other");
        }

    }

    public function getTeacher(Request $request){   // 教师详细信息页
        return view("register.teacher");
    }

    public function postSaveTeacher(Request $request){
        $this->validate($request, [
            "img_upload" => "required",
        ]);

        $user = Auth::user();
        $info = UserInfoController::get_user_info($user);
        $info->img_upload = FileHelper::saveUserImage($user, $request->file("img_upload"), "certificate");
        $info->update();
        
        $old_cr = CertRequest::where("user_id", "=", $user->id)->where("status","<>",2)->get();
        
        if(count($old_cr) == 0){
            $cr = new CertRequest;
            $cr->user_id = $user->id;
            $cr->status = 0;
            $cr->save();
            Session::flash("success", "教师认证申请提交成功");
        }

        return redirect()->route("register.welcome")->withRole("teacher");
    }


    public function getWelcome(Request $request){
        $info = UserInfoController::get_user_info(Auth::user());
        return view("register.welcome")->withRole($info->role);
    }

    // public function getAuthor(Request $request){    // 作者详细信息页
    //     return view("register.author");
    // }

    // public function postSaveAuthor(Request $request){
    //     // save author
    //     return view("register.welcome")->withRole("author");
    // }

    
}
