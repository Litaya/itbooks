<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CertRequest;
use App\Models\UserInfo;
use App\Models\User;
use Session;


class CertRequestAdminController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $user = Auth::user();
		// TODO: check user permission here!

		$certs = [];
		if($request->search){
			$search = $request->search;
			$certs = DB::table('cert_request')
							->join('user_info', 'cert_request.user_id', '=', 'user_info.user_id')
                            ->join('user', 'user_info.user_id', '=', 'user.id')
							->select('user.username username, user_info.*')
							->where('user_info.realname', 'like', "%$search%")
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
}
