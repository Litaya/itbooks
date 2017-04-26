<?php

namespace App\Http\Controllers;

use App\Libraries\WechatMessageSender;
use Illuminate\Http\Request;
use App\Models\CertRequest;
use App\Models\Certification; // as wrapper
use App\Models\UserInfo;
use App\Models\User;
use Session;
use DB;


class CertRequestAdminController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $certs = [];
        if($request->search){
            $search = $request->search;
            $certs = DB::table('cert_request')
                        ->join('user_info', 'cert_request.user_id', '=', 'user_info.user_id')
                        ->join('user', 'user_info.user_id', '=', 'user.id')
                        ->select('cert_request.id as id', 'user.username as username', 'user_info.realname as realname', 'user_info.role as cert_name', 'user_info.workplace as workplace', 'cert_request.status as status')
                        ->where('user_info.realname', 'like', "%$search%")
                        ->orWhere('user.username', 'like', "%$search%")
                        ->orderBy('id', 'desc')
                        ->paginate(20);
        }
        else{
            $certs = DB::table('cert_request')
                        ->join('user_info', 'cert_request.user_id', '=', 'user_info.user_id')
                        ->join('user', 'user_info.user_id', '=', 'user.id')
                        ->select('cert_request.id as id', 'user.username as username', 'user_info.realname as realname', 'user_info.role as cert_name', 'user_info.workplace as workplace', 'cert_request.status as status')
                        ->orderBy('id', 'desc')
                        ->paginate(20);
        }

        for($i=0;$i<count($certs);$i++)
            $certs[$i] = (object) $certs[$i];

        return view("admin.certificate.index")->withCerts($certs);

    }

    public function show($id){
        $cr = CertRequest::find($id);
        $user = User::find($cr->user_id);

        // generate certification
        $userinfo = UserInfoController::get_user_info($user);
        $cert = new Certification;
        $cert->id = $cr->id;
        $cert->realname = $userinfo->realname;
        $cert->cert_name = $userinfo->role;
        $cert->status = $cr->status;
        $cert->img_upload = $userinfo->img_upload;
        $json = json_decode($userinfo->json_content, true);
        $json['phone'] = $userinfo->phone;
        $cert->json_content = $json;
        $cert->workplace = $userinfo->workplace;

        return view("admin.certificate.show")->withCert($cert);
    }

    public function pass($id){
		// TODO: check user permission here!

		$cr = CertRequest::find($id);
		if($cr->status == 0){
			$user = User::find($cr->user_id);
            $userinfo = UserInfoController::get_user_info($user);
			if(empty($user->certificate_as) or strpos($user->certificate_as, $userinfo->role) === FALSE){
				if(!empty($user->certificate_as) and strlen($user->certificate_as) > 0) $user->certificate_as = $user->certificate_as . "|";
                else $user->certificate_as = "";

				$user_json = [];
				if(!empty($user->json_content))
					$user_json = json_decode($user->json_content, true);
				if(strtoupper($userinfo->role) == 'TEACHER')
					$user_json['teacher'] = ['book_limit'=>10];
                if(strtoupper($userinfo->role) == 'AUTHOR')
					$user_json['teacher'] = ['book_limit'=>10];
				$user->json_content = json_encode($user_json);

				$user->certificate_as = $user->certificate_as . strtoupper($userinfo->role);
				$user->update();
			}
			$cr->status = 1;
			$cr->update();

			WechatMessageSender::sendText(Auth::user()->openid,
				"您的教师资格已经认证通过，您现在可以进行样书申请，并使用其他功能。\n".
				"<a href='https://itbook.kuaizhan.com/39/60/p332015340738c5'>新手指南</a>");

			Session::flash('success', "已批准申请");
		}
		else Session::flash('warning', "该申请已经过期");

		return redirect()->route("admin.cert.index");
	}

	public function reject($id, Request $request){
		$cr = CertRequest::find($id);
		$this->validate($request, ["message"=>"max:250"]);
		if($cr->status == 0){
			$cr->status = 2;
			$cr->message = $request->message;
			$cr->update();
			WechatMessageSender::sendText(Auth::user()->openid,
				"您的身份认证申请被拒绝，决绝理由:\n".$request->message);
			Session::flash('success', "已拒绝申请");
		}
		else Session::flash('warning', "该申请已经过期");

		return redirect()->route("admin.cert.index");
	}

	public function deprive($id, Request $request){
		$cr = CertRequest::find($id);
		if($cr->status == 1){
			$cr->status = 2; // deprivated // used to be 3!
			$cr->update();

            $user = User::find($cr->user_id);
            $user->certificate_as = "";
            $user->update();

			Session::flash('success', "此身份认证已作废");
		}
		else Session::flash('warning', "该申请未审批或未通过");

		return redirect()->route("admin.cert.index");
	}

    public function destroy($id){
        return redirect()->back();
    }
}
