<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use App\Models\BookRequest;
use DB;

class BookRequestAdminController extends Controller
{

    public function __construct(){
        $this->middleware('auth');  // TODO: change to admin later
    }

    public function getIndex(Request $request){
        // $privilege = Auth::user()->permission_string;    // TODO: add permission control here

        if($request->search){
            $search = $request->search;
			$bookreqs = DB::table('book_request')
							->join('user', 'book_request.user_id', '=', 'user.id')
                            ->join('book', 'book_request.book_id', '=', 'book.id')
							->select('book_request.*')
							->where('user.username', 'like', "%$search%")
                            ->orWhere('book.name', 'like', "%$search%")
                            ->orderBy('id', 'desc')
                            //->orWhere('book_request.realname', 'like', "%$search%")
							->paginate(20);
			
			for($i=0; $i<count($bookreqs); $i++){
				$br = (new BookRequest)->newFromBuilder($bookreqs[$i]);
				$bookreqs[$i] = $br;
			}
        }

        else $bookreqs = BookRequest::orderBy('id', 'desc')->paginate(20);
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

	        $user      = $bookreq->user;
	        $user_json = $bookreq->user->json_content;
	        $user_json = json_decode($user_json,true);
	        $user_json['teacher']['book_limit'] -- ;
	        $user->json_content = json_encode($user_json);
	        $user->save();

            Session::flash('success', '您通过了一项样书申请');
        }
        else
            Session::flash('warning', '此申请已经被审批过');

        return redirect()->route("admin.bookreq.index");
    }

    public function reject($id){
        $bookreq = BookRequest::find($id);
        if($bookreq->status == 0){
            $bookreq->status = 2;
            $bookreq->update();
            Session::flash('success', '您拒绝了一项样书申请');
        }
        else
            Session::flash('warning', '此申请已经被审批过');

        return redirect()->route("admin.bookreq.index");
    }

    public function destroy($id){
        $req = BookRequest::find($id);
        $req->delete();

        Session::flash('success', '您删除了一个样书申请');
        
        return redirect()->route('bookreq.index');
    }
}
