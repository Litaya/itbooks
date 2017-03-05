<?php

namespace App\Http\Controllers;

use App\Mail\EmailCertificate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
	public function send()
	{
		Mail::to("1369918998@qq.com")->send(new EmailCertificate(Auth::user()));
	}

	public function certificate(Request $request,$token){

		$token_arr = explode(",",$token);
		$user_id   = $token_arr[0];
		$user = User::where('id',$user_id)->first();
		$new_token = "$user_id,".sha1($user->username.$user->email);

		if($new_token == $token) {
			Auth::login($user);
			$user->email_status = 1;
			$user->save();
			$request->session()->flash('notice_message', '您的邮箱已验证成功');
			$request->session()->flash('notice_status', 'success');
			return redirect()->route('home');
		}else{
			$request->session()->flash('notice_message', '验证失败');
			$request->session()->flash('notice_status', 'danger');
		}

		return redirect()->route('home');
	}
}
