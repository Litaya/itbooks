<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use App\Models\Conference;
use App\Models\ConferenceRegister;

class ConferenceController extends Controller
{
    public function index(){
        $now = date('Y-m-d');
        $cs = Conference::where('time', '>', $now)->orderBy('time', 'desc')->paginate(10);
        return view('conference.index')->withConferences($cs);
    }

    public function show($id){
        $c = Conference::find($id);
        $cr = Auth::check()?
              ConferenceRegister::where('user_id', '=', Auth::id())->where('conference_id', '=', $c->id)->first() :
              null;
        return view('conference.show')->withConference($c)->withRegister($cr);
    }

    public function postRegister(Request $request, $conf_id){
        $this->validate($request, [
            "name" => "required|max:10",
            "school" => "required|max:20", 
            "position" => "required|max:10",
            "job_title" => "required|max:10",
            "phone" => "required|digits_between:11,15",
            "email" => "required|email",
            "invoice_title" => "required|max:127",
            "mail_address" => "required|max:255",
        ]);

        $cr = new ConferenceRegister;
        $cr->user_id = Auth::id();
        $cr->conference_id = $conf_id;

        $old_cr = ConferenceRegister::where('user_id', '=', $cr->user_id)->where('conference_id', '=', $cr->conference_id)->get();
        if(count($old_cr) == 0){
            $cr->name = $request->name;
            $cr->school = $request->school;
            $cr->position = $request->position;
            $cr->job_title = $request->job_title;
            $cr->phone = $request->phone;
            $cr->email = $request->email;
            $cr->invoice_title = $request->invoice_title;
            $cr->mail_address = $request->mail_address;
            $cr->save();
            Session::flash('success', '注册成功');
        }
        else{
            Session::flash('warning', '您已报名过本次会议，若需要修改信息请先取消上次记录');
        }

        return redirect()->route('conference.show', $conf_id);
    }

    public function postCancel($conf_id){
        $user_id = Auth::id();
        $crs = ConferenceRegister::where('user_id', '=', $user_id)->where('conference_id', '=', $conf_id)->get();
        for($i=0;$i<count($crs);$i++)
            $crs[$i]->delete();
        
        Session::flash('success', '取消报名成功');

        return redirect()->route('conference.show', $conf_id);
    }
}
