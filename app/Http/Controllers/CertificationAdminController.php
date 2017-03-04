<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\Certification;
use App\Models\User;


class CertificationAdminController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $user = Auth::user();
        // TODO: check user permission here! 
        
        $open_certs = Certification::where("status", "=", 0)->paginate(10);
        return view("admin.certificate.index")->withCerts($open_certs);
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
                $user->certificate_as = $user->certificate_as . $cert->cert_name;
                $user->update();
            }
            $cert->status = 1;
            $cert->update();
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

}
