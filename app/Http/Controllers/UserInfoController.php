<?php

namespace App\Http\Controllers;

use App\Libraries\WechatMessageSender;
use Illuminate\Http\Request;
use App\Helpers\FileHelper;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Certification;
use App\Models\CertRequest;
use App\Models\District;
use Auth;
use Session;


class UserInfoController extends Controller
{

    private $basic_required = ["email", "phone", "role"];

    private $teacher_required = ["realname", "workplace", "department", "jobtitle", 
                     "img_upload", "course_info"];

    private $author_required =  ["realname", "workplace", "img_upload"];

    public static function get_user_info($user, $expand_json=true){

        // 取出user_info表中携带的信息
        $userinfo = UserInfo::where("user_id", "=", $user->id)->first();

        // 如果此用户没有user_info，创建一个；注意user_id是user_info的主键
        if(empty($userinfo)){
            $userinfo = new UserInfo;
            $userinfo->user_id = $user->id;
            $userinfo->save();

            // 重新加载$userinfo，因为执行过save()的实例不可再用
            $userinfo = UserInfo::where("user_id", "=", $user->id)->first();
        }

        // 将user表中携带的信息追加到$userinfo实例
        $userinfo->email = $user->email;
        $userinfo->certificate_as = $user->certificate_as;
        
        // 将json_content中携带的信息追加到$userinfo实例
        if($expand_json){
            // 展开的优点是view层可以通过laravel的Form::model来直接显示

            $data = empty($userinfo->json_content) ? [] : json_decode($userinfo->json_content, true);
            if( (!empty($data["course_name_1"]) && !empty($data["number_stud_1"])) ||
                (!empty($data["course_name_2"]) && !empty($data["number_stud_2"])) ||
                (!empty($data["course_name_3"]) && !empty($data["number_stud_3"])) )
            {
                $userinfo->course_info = true;
            }

            $get = function($array, $key) { return array_key_exists($key, $array) ? $array[$key] : null; };
            $userinfo->course_name_1 = $get($data, "course_name_1");
            $userinfo->number_stud_1 = $get($data, "number_stud_1");
            $userinfo->course_name_2 = $get($data, "course_name_2");
            $userinfo->number_stud_2 = $get($data, "number_stud_2");
            $userinfo->course_name_3 = $get($data, "course_name_3");
            $userinfo->number_stud_3 = $get($data, "number_stud_3");
            $userinfo->book_plan = $get($data, "book_plan");
            $userinfo->department = $get($data, "department");
            $userinfo->jobtitle = $get($data, "jobtitle");
            $userinfo->qqnumber = $get($data, "qqnumber");
            $userinfo->position = $get($data, "position");
        }

        return $userinfo;
    }

    public static function update_user_info($new_info){
        $user = User::find($new_info->user_id);
        //$userinfo = UserInfo::where("user_id", "=", $new_info->user_id)->first();
        $userinfo = UserInfo::find($user->id);

        if($user->email != $new_info->email){
            $user->email = $new_info->email;
            $user->email_status = 0;
            $user->update();
        }

        $userinfo->role = $new_info->role;
        $userinfo->phone = $new_info->phone;
        $userinfo->realname = $new_info->realname;
        $userinfo->workplace = $new_info->workplace;
        $userinfo->address = $new_info->address;
        $userinfo->json_content = $new_info->json_content;
        $userinfo->province_id = $new_info->province_id;
        if($userinfo->province_id == "") $userinfo->province_id = null;
        $userinfo->city_id = $new_info->city_id;
        if($userinfo->city_id == "") $userinfo->city_id = null;
        $userinfo->img_upload = $new_info->img_upload;

        $userinfo->update();
    }


    public function getBasic(){
        $userinfo = self::get_user_info(Auth::user());  // 需要显示，默认展开json_content，下同
        $lockrole = false;
        $admissions = CertRequest::where("user_id", "=", Auth::id())->where("status", "<>", 2)->get();
        if(count($admissions) > 0) $lockrole = true;
        return view("userinfo.basic")->withUserinfo($userinfo)->withLockrole($lockrole);
    }

    public function getDetail(){
        $userinfo = self::get_user_info(Auth::user());
        return view("userinfo.detail")->withUserinfo($userinfo);
    }
    

    public function getAuthor(){
        $userinfo = self::get_user_info(Auth::user());
        if($userinfo->role != "author")
            return redirect()->route("userinfo.basic");
        return view("userinfo.author")->withUserinfo($userinfo);
    }
    
    public function getTeacher(){
        $userinfo = self::get_user_info(Auth::user());
        if($userinfo->role != "teacher")
            return redirect()->route("userinfo.basic");
        return view("userinfo.teacher")->withUserinfo($userinfo);
    }

    public function getMissing(Request $request){
        $info = self::get_user_info(Auth::user());
        $role = $info->role;

        $missing = [];

        if($role == "teacher"){
            foreach($this->teacher_required as $key){
                if(!isset($info, $key) or empty($info->$key))
                    array_push($missing, $key);
            }

            if( (empty($info->course_name_1)) and 
                (empty($info->course_name_2)) and
                (empty($info->course_name_3)) )
            {
                array_push($missing, "course");
            }
            
        }

        else if($role == "author"){
            foreach($this->teacher_required as $key){
                if(!isset($info, $key) or empty($info->$key))
                    array_push($missing, $key);
            }
        }

        else {
            $role_name = "未设置";
            if(empty($role) or $role == "other") $role_name = "其他";
            else if($role == "student") $role_name = "学生";
            else if($role == "staff") $role_name = "职员";
            Session::flash("warning", "您当前的角色\"".$role_name."\"不能提交申请");
            return redirect()->route("userinfo.basic");
        }


        if(count($missing) == 0){
            Session::flash("warning", "您已经提交了验证所需的全部材料，如有信息变动请到个人中心修改");
            return redirect()->route("userinfo.basic");
        }

        return view("userinfo.missing")
               ->withMissing($missing)
               ->withCerttype($role)
               ->withUserinfo($info);
    }



    public function postSaveBasic(Request $request){
        $this->validate($request, [
            "email" => "required|email",
            "phone" => "required",
            "role" => "required|in:teacher,author,student,staff,other",
        ]);

        $userinfo = self::get_user_info(Auth::user(), false); // 只修改不显示，不需要展开
        // if(empty($userinfo->role) and empty($request->role)){
        //     return redirect()->back()->withErrors("角色字段必须选择");
        // }
        if($request->email != $userinfo->email)
        {
            $this->validate($request, [
                "email" => "required|email|unique:user,email",
            ]);
            $userinfo->email = $request->email;
        }
        
        $userinfo->phone = $request->phone;
        $userinfo->role = $request->role;
        self::update_user_info($userinfo);

        Session::flash("success", "信息保存成功");

        return redirect()->route("userinfo.basic");
    }


    public function postSaveDetail(Request $request){
        $userinfo = self::get_user_info(Auth::user(), false); // 只修改不显示，不需要展开
        $userinfo->realname = $request->realname;
        $userinfo->workplace = $request->workplace;
        $userinfo->address = $request->address;

        $userinfo->province_id = $request->province;
        $userinfo->city_id = $request->city;

        $data = empty($userinfo->json_content) ? [] : json_decode($userinfo->json_content, true);
        $data["qqnumber"] = $request->qqnumber;
        $userinfo->json_content = json_encode($data);

        self::update_user_info($userinfo);
        
        Session::flash("success", "信息保存成功");

        return redirect()->route("userinfo.detail");
    }

    public function postSaveTeacher(Request $request){
        $userinfo = self::get_user_info(Auth::user(), false); // 只修改不显示，不需要展开
        if($userinfo->role != "teacher"){
            Session::flash('warning', "您没有选择教师角色，请回到本页进行选择");
            return redirect()->route('userinfo.basic');
        }

        $userinfo->realname = $request->realname;
        $userinfo->workplace = $request->workplace;

        if($request->img_upload)
            $userinfo->img_upload = FileHelper::saveUserImage(Auth::user(), $request->file("img_upload"), "certificate");

        if($request->number_stud_1 && !$request->course_name_1)
			return redirect()->back()->withErrors("请填写第一课程名称")->withInput();
        if(!$request->number_stud_1 && $request->course_name_1)
			return redirect()->back()->withErrors("未填写第一课程学生人数")->withInput();
		if($request->number_stud_2 && !$request->course_name_2)
			return redirect()->back()->withErrors("请填写第二课程名称")->withInput();
        if(!$request->number_stud_2 && $request->course_name_2)
			return redirect()->back()->withErrors("未填写第二课程学生人数")->withInput();
		if($request->number_stud_3 && !$request->course_name_3)
			return redirect()->back()->withErrors("请填写第三课程名称")->withInput();
		if(!$request->number_stud_3 && $request->course_name_3)
			return redirect()->back()->withErrors("未填写第三课程学生人数")->withInput();

        $data = empty($userinfo->json_content) ? [] : json_decode($userinfo->json_content, true);
        $data["course_name_1"] = $request->course_name_1;
        $data["number_stud_1"] = $request->number_stud_1;
        $data["course_name_2"] = $request->course_name_2;
        $data["number_stud_2"] = $request->number_stud_2;
        $data["course_name_3"] = $request->course_name_3;
        $data["number_stud_3"] = $request->number_stud_3;
        $data["jobtitle"] = $request->jobtitle;
        $data["position"] = $request->position;
        $data["department"] = $request->department;
        $userinfo->json_content = json_encode($data);

        self::update_user_info($userinfo);

	    if(!$userinfo->img_upload){
		    WechatMessageSender::sendText(Auth::user()->openid,
			    "您还没有上传认证照片，目前暂时无法申请样书，但可以使用其他功能，请尽快上传照片完成认证。\n".
			    "<a href='https://itbook.kuaizhan.com/39/60/p332015340738c5'>新手指南</a>");
	    }else{
		    WechatMessageSender::sendText(Auth::user()->openid,
			    "您已经提交了认证信息，我们将于一个工作日内完成审核！您目前暂时无法申请样书，但可以使用其他功能。\n".
			    "<a href='https://itbook.kuaizhan.com/39/60/p332015340738c5'>新手指南</a>");
	    }

	    if($request->sendrequest == "true"){
            return self::createCertRequest(self::get_user_info(Auth::user(), true));
        }

        Session::flash("success", "信息保存成功");

        return redirect()->route("userinfo.teacher");
    }

    public function postSaveAuthor(Request $request){
        $userinfo = self::get_user_info(Auth::user(), false); // 只修改不显示，不需要展开
        if($userinfo->role != "author"){
            Session::flash('warning', "您没有选择作者角色，请回到本页进行选择");
            return redirect()->route('userinfo.basic');
        }

        $userinfo->realname = $request->realname;
        $userinfo->workplace = $request->workplace;
        if($request->img_upload){
            $userinfo->img_upload = FileHelper::saveUserImage(Auth::user(), $request->file("img_upload"), "certificate");
        }

        $data = empty($userinfo->json_content) ? [] : json_decode($userinfo->json_content, true);
        $data["book_plan"] = $request->book_plan;
        $userinfo->json_content = json_encode($data);

        self::update_user_info($userinfo);

        if($request->sendrequest == "true"){
            return self::createCertRequest(self::get_user_info(Auth::user(), true));
        }

        return redirect()->route("userinfo.author");
    }
    

    public function postSaveMissing(Request $request){

        // lambdas
        $set_array = function(&$array, $key, $new_value){
            $array[$key] = empty($new_value) ? $array[$key] : $new_value;
        };

        $set_obj = function(&$obj, $key, $new_value){
            $obj->$key = empty($new_value) ? $obj->$key : $new_value;
        };

        $userinfo = self::get_user_info(Auth::user(), false); // 只修改不显示，不需要展开

        $data = json_decode($userinfo->json_content, true);
        $set_array($data, "course_name_1", $request->course_name_1);
        $set_array($data, "course_name_2", $request->course_name_2);
        $set_array($data, "course_name_3", $request->course_name_3);
        $set_array($data, "number_stud_1", $request->number_stud_1);
        $set_array($data, "number_stud_2", $request->number_stud_2);
        $set_array($data, "number_stud_3", $request->number_stud_3);
        $set_array($data, "department", $request->department);
        $set_array($data, "jobtitle", $request->jobtitle);
        $userinfo->json_content = json_encode($data);
        
        $set_obj($userinfo, "workplace", $request->workplace);
        $set_obj($userinfo, "realname", $request->realname);
        if($request->img_upload)
            $userinfo->img_upload = FileHelper::saveUserImage(Auth::user(), $request->file("img_upload"), "certificate");

        self::update_user_info($userinfo);

        Session::flash("success", "信息保存成功");

        return redirect()->route("userinfo.basic");
    }

    private function createCertRequest($info){
        $info->id_type = $info->role;

        $user = Auth::user();
        if($info->role == "teacher"){
            if(
                empty($info->realname) or
                empty($info->workplace) or
                empty($info->img_upload) or
                (empty($info->course_name_1) and empty($info->course_name_2) and empty($info->course_name_3)) or
                (empty($info->number_stud_1) and empty($info->number_stud_2) and empty($info->number_stud_3)) or
                empty($info->jobtitle) or
                empty($info->phone) or
                empty($info->department)
            ){
                Session::flash("warning", "认证所需的信息不全，请您在本页补完");
                return redirect()->route("userinfo.missing");
            }
        }
        else if($info->role == "author")
        {
            if(
                empty($info->realname) or
                empty($info->workplace) or
                empty($info->img_upload) or
                empty($info->phone)
            ){
                Session::flash("warning", "认证所需的信息不全，请您在本页补完");
                return redirect()->route("userinfo.missing");
            }
        }


        // check duplicate
        $tmp = CertRequest::where("user_id", "=", $user->id)->where("status","<>", 2)->first();
        if(empty($tmp)){
            $cr = new CertRequest;
            $cr->user_id = $user->id;
            $cr->status = 0;
            $cr->save();
            Session::flash("success", "提交申请成功");
        }
        else{
            if($tmp->status == 0)
                Session::flash("warning", "您已经提交过申请，请耐心等待管理员审核");
            else if($tmp->status == 1)
                Session::flash("warning", "您已经通过了审批，请不要重复审批");
        }

        if($info->role=="teacher")
            return redirect()->route("userinfo.teacher");
        else if($info->role=="author")
            return redirect()->route("userinfo.author");
        
        else
            return redirect()->route("userinfo.basic");

    }

}
