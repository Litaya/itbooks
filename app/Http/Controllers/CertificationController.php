<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\Certification;

class CertificationController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $user_cert = $user->certificate_as;

        $selection = "both";

        if($user_cert != null || strlen($user_cert) > 0){
            $show_teacher_flag = true;
            $show_author_flag = true;
            
            if(strpos($user_cert, 'AUTHOR')) $show_author_flag = false;
            if(strpos($user_cert, 'TEACHER')) $show_teacher_flag = false;
            if(!$show_author_flag && !$show_teacher_flag)
                $selection = "none";
        }
        else {
            $unfin_cert = Certification::where("user_id", "=", $user->id)->and("status", "=", "0")->get();
            if(count($unfin_cert)>0)
                $selection = "exist";
        }
        
        return view("certificate.index")->withSelection($selection);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $user_cert = $user->certificate_as;

        $selection = "both";

        $unfin_cert = Certification::where("user_id", "=", $user->id)->where("status", "=", 0)->get();
        if(count($unfin_cert) > 0) $selection = "exist";
        else if($user_cert != null || strlen($user_cert) > 0){
            $show_teacher_flag = true;
            $show_author_flag = true;
            
            if(strpos($user_cert, 'AUTHOR')!==false) {$show_author_flag = false; $selection = "teacher";}
            if(strpos($user_cert, 'TEACHER')!==false) {$show_teacher_flag = false; $selection = "author";}
            if(!$show_author_flag && !$show_teacher_flag)
                $selection = "none";
        }

        
        return view("certificate.create")->withSelection($selection);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            "realname" => "required",
            "id_number" => "required|alpha_dash",
            "id_type" => "required|in:author,teacher",
            "img_upload" => "required"
        ]);
        
        $unfin_cert = Certification::where("user_id", "=", $user->id)->where("status", "=", 0)->get();
        if(count($unfin_cert)==0){
            $cert = new Certification;
            $cert->cert_name = strtoupper($request->id_type);
            $cert->user_id = $user->id;
            $cert->status = 0;
            $cert->save();
            Session::flash('success', '您的身份认证申请提交成功');
        }
        else Session::flash('warning', '您有未关闭的身份认证申请');

        return redirect()->route("cert.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
