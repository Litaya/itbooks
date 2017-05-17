<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\Certification;
use App\Models\User;
use DB;
use Illuminate\Pagination\Paginator;

class CertificationAdminController extends Controller
{
	public function __construct(){
		$this->middleware('auth');
	}

	public function index(Request $request){
		$user = Auth::user();
		// TODO: check user permission here!

		$certs = [];
		if($request->search){
			$search = $request->search;
			$certs = DB::table('certification')
							->join('user', 'certification.user_id', '=', 'user.id')
							->select('certification.*')
							->where('certification.realname', 'like', "%$search%")
							->orWhere('user.username', 'like', "%$search%")
							->paginate(20);
			
			for($i=0; $i<count($certs); $i++){
				$c = (new Certification)->newFromBuilder($certs[$i]);
				$certs[$i] = $c;
			}
		}
		else $certs = Certification::orderBy('id', 'desc')->paginate(20);

		//else $open_certs = Certification::where("status", "=", 0)->paginate(20); // show open certification request only
		return view("admin.certificate.index")->withCerts($certs);
	}

	public function show($id){
		$cert = Certification::find($id);
		$cert->json_content = json_decode($cert->json_content, true);
		return view('admin.certificate.show')->withCert($cert);
	}


	public function pass($id){
		// TODO: check user permission here!

		$cert = Certification::find($id);
		if($cert->status == 0){
			$user = User::find($cert->user_id);
			if(strpos($user->certificate_as, $cert->cert_name) === FALSE){
				if(strlen($user->certificate_as) > 0) $user->certificate_as = $user->certificate_as . "|";

				$user_json = [];
				if(!empty($user->json_content))
					$user_json = \GuzzleHttp\json_decode($user->json_content,true);
				if($cert->cert_name == 'TEACHER')
					$user_json['teacher'] = ['book_limit'=>10];
				$user->json_content = \GuzzleHttp\json_encode($user_json);

				$user->certificate_as = $user->certificate_as . $cert->cert_name;
				$user->update();
			}
			$cert->status = 1;
			$cert->update();

			// 同步user_info的信息
			$cert_json = \GuzzleHttp\json_decode($cert->json_content);
			$data = [
				'school_name' => $cert->workplace,
				'school_division' => $cert_json->department,
				'school_title'    => $cert_json->jobtitle,
				'school_json'     => \GuzzleHttp\json_encode([
					"course_name_1" => $cert_json->course_name_1,
					"number_stud_1" => $cert_json->number_stud_1,
					"course_name_2" => $cert_json->course_name_2,
					"number_stud_2" => $cert_json->number_stud_2,
					"course_name_3" => $cert_json->course_name_3,
					"number_stud_3" => $cert_json->number_stud_3,
				]),
				'phone'           => $cert_json->phone,
				'qq'              => $cert_json->qqnumber,
				'realname'        => $cert->realname
			];
			UserInfo::where('user_id',$user->id)->update($data);

			Session::flash('success', "已批准申请");
		}
		else Session::flash('warning', "该申请已经过期");

		return redirect()->route("admin.cert.index");
	}

	public function reject($id, Request $request){
		$cert = Certification::find($id);
		$this->validate($request, ["message"=>"max:250"]);
		if($cert->status == 0){
			$cert->status = 2;
			$cert->message = $request->message;
			$cert->update();
			Session::flash('success', "已拒绝申请");
		}
		else Session::flash('warning', "该申请已经过期");

		return redirect()->route("admin.cert.index");
	}

	public function deprive($id, Request $request){
		$cert = Certification::find($id);
		if($cert->status == 1){
			$cert->status = 3; // deprivated
			$cert->update();
			Session::flash('success', "此身份认证已作废");
		}
		else Session::flash('warning', "该申请未审批或未通过");

		$user = $cert->user;
		if(strpos($user->certificate_as, "TEACHER") !== FALSE){
			if(strpos($user->certificate_as, "AUTHOR") !== FALSE) $user->certificate_as = "AUTHOR";
			else $user->certificate_as = "";
		}
		$user->update();

		return redirect()->route("admin.cert.index");
	}

	public function destroy($id)
    {
        $c = Certification::find($id);
        $c->delete();
        return redirect()->route('admin.cert.index');
    }
}
