<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\BookRequest;

class BookRequestAdminController extends Controller
{

    public function __construct(){
        $this->middleware('auth');  // TODO: change to admin later
    }

    public function getIndex(Request $request){
        // $privilege = Auth::user()->permission_string;    // TODO: add permission control here

        $bookreqs = BookRequest::all();
        return view('admin.book_request.index')->withBookreqs($bookreqs);
    }

    public function show($id){
        $bookreq = BookRequest::find($id);
        return view('admin.book_request.show')->withBookreq($bookreq);
    }

    public function pass($id){
        $bookreq = BookRequest::find($id);
        if($bookreq->status == 0){
            $bookreq->status = 1;
            $bookreq->update();
            Session::flash('success', '您通过了一项样书申请');
        }
        else
            Session::flash('warning', '此申请已经被审批过');

        return redirect()->route("admin.bookreq.index");
    }
}
