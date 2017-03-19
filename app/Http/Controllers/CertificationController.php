<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Image;
use File;

use App\Models\Certification;
use App\Helpers\FileHelper;

class CertificationController extends Controller
{

	public function __construct()
	{
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
		$certificate_as = $user->certificate_as;
		$unfin_cert = Certification::where("user_id", "=", $user->id)->orderBy('updated_at','desc')->get();

		$success_records  = [];
		$rejected_records = [];
		$waiting_records  = [];

		if(count($unfin_cert) > 0){
			foreach ($unfin_cert as $cert){
				switch ($cert->status){
					case 0:
						array_push($waiting_records,$cert);
						break;
					case 1:
						array_push($success_records,$cert);
						break;
					case 2:
						array_push($rejected_records,$cert);
						break;
					default:
						break;
				}
			}
		}

		$certificate_as = explode('|',$certificate_as);
		$identities = [];
		foreach ($certificate_as as $certification){
			switch ($certification){
				case 'TEACHER':
					array_push($identities,'教师');
					break;
				case 'AUTHOR':
					array_push($identities,'作者');
					break;
				default:
					break;
			}
		}
		$certifications = $unfin_cert;
		return view("certificate.index")->with(compact('success_records','rejected_records','waiting_records','identities','certifications'));
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
			// "id_number" => "required|alpha_dash",
			"workplace" => "required",
			"id_type" => "required|in:author,teacher",
			"img_upload" => "required"
		]);

		if($request->id_type=="teacher"){
			$this->validate($request, [
				"course_name_1" => "required",
				"number_stud_1" => "required|integer",
				"department" => "required",
				"jobtitle" => "required",
				"qqnumber" => "digits_between:5,12",
				"phone" => "required",
			]);
		};

		if($request->number_stud_2 && !$request->course_name_2)
			return redirect()->back()->withErrors("请填写第二课程名称")->withInput();
		if($request->number_stud_3 && !$request->course_name_3)
			return redirect()->back()->withErrors("请填写第三课程名称")->withInput();
		if(!$request->number_stud_2 && $request->course_name_2)
			return redirect()->back()->withErrors("未填写第二课程学生人数")->withInput();
		if(!$request->number_stud_3 && $request->course_name_3)
			return redirect()->back()->withErrors("未填写第三课程学生人数")->withInput();


		$data = [
			"course_name_1" => $request->course_name_1,
			"number_stud_1" => $request->number_stud_1,
			"course_name_2" => $request->course_name_2,
			"number_stud_2" => $request->number_stud_2,
			"course_name_3" => $request->course_name_3,
			"number_stud_3" => $request->number_stud_3,
			"jobtitle" => $request->jobtitle,
			"department" => $request->department,
			"qqnumber" => $request->qqnumber,
			"phone" => $request->phone,
		];

		$jdata = json_encode($data);

		$unfin_cert = Certification::where("user_id", "=", $user->id)->where("status", "=", 0)->get();
		if(count($unfin_cert)==0){
			$cert = new Certification;
			$cert->realname = $request->realname;
			// $cert->id_number = $request->id_number;
			$cert->workplace = $request->workplace;
			$cert->cert_name = strtoupper($request->id_type);
			$cert->user_id = $user->id;
			$cert->status = 0;
			$cert->json_content = $jdata;
			$cert->img_upload = FileHelper::saveUserImage($user, $request->file("img_upload"), "certificate");
			$cert->save();
			Session::flash('success', '您的身份认证申请提交成功');
		}else
			Session::flash('warning', '您有未关闭的身份认证申请');

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
		$c = Certification::find($id);
		$c->delete();
		return redirect()->route('index');
	}
}
