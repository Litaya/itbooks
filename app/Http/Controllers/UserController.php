<?php

namespace App\Http\Controllers;

use App\Mail\EmailCertificate;
use App\Models\Certification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
	public function index(Request $request){
		return view('user/index');
	}

	// 教师信息页
	public function teacher(Request $request){
		$user = Auth::user();
		$user->json_content = \GuzzleHttp\json_decode($user->json_content);
		$cert = Certification::where(['user_id'=>$user->id,'cert_name'=>'TEACHER'])->first();
		$cert->json_content =  \GuzzleHttp\json_decode($cert->json_content);
		return view('user/teacher',compact('cert','user'));
	}

	public function email(Request $request){
		return view('user/email');
	}

	public function storeEmail(Request $request){
		$this->validate($request,[
			'email'=>'required'
		]);
		$user = Auth::user();
		$email = $request->get('email');
		$user->email = $email;
		$user->email_status = 0;
		$user->save();
		Mail::to($user->email)->send(new EmailCertificate($user));

		$request->session()->flash('notice_status','success');
		$request->session()->flash("notice_message",'验证邮件已发送，请登录邮箱查看');
		return redirect()->route('user.email');
	}

	public function sendEmailCert(Request $request){
		$user = Auth::user();
		Mail::to($user->email)->send(new EmailCertificate($user));
		$request->session()->flash('notice_status','success');
		$request->session()->flash("notice_message",'验证邮件已发送，请登录邮箱查看');
		return redirect()->route('user.email');
	}

	public function address(Request $request){
		$user = Auth::user();
		if(isset($user->json_content))
			$user->json_content = \GuzzleHttp\json_decode($user->json_content);
		return view('user/address',compact('user'));
	}
}
