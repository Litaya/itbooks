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

        // $ar = Session::get('adminrole');
        $admin = \App\Models\Admin::where('user_id','=',Auth::id())->first(); // must succeed, or this user is not an administer.
        $ar = $admin->role;
        

        if($request->search){
            $search = $request->search;
            switch($ar){
                case "SUPERADMIN":
                    $bookreqs = BookRequest::join('user', 'book_request.user_id', '=', 'user.id')
                                            ->join('book', 'book_request.book_id', '=', 'book.id')
                                            ->where('user.username', 'like', "%$search%")
                                            ->orWhere('book.name', 'like', "%$search%")
                                            ->orderBy('id', 'desc')
                                            ->paginate(20, ['book_request.*']);
                    break;

                case "DEPTADMIN":
                    $code = \App\Models\Department::find($admin->department_id)->code;
                    $bookreqs = BookRequest::ofDepartmentCode($code)
                                            ->join('book', 'book_request.book_id', '=', 'book.id')
                                            ->join('user', 'book_request.user_id', '=', 'user.id')
                                            ->where('user.username', 'like', "%$search%")
                                            ->orWhere('book.name', 'like', "%$search%")
                                            ->orderBy('id', 'desc')
                                            ->paginate(20, ['book_request.*']);
                    break;
                
                case "EDITOR": // currently unknown
                    break;
                
                default: // reaching here should cause an error
                    break;
            }

        }

        else {
            switch($ar){
                case "SUPERADMIN":
                    $bookreqs = BookRequest::orderBy('id', 'desc')->paginate(20);
                    break;
                case "DEPTADMIN":
                    $code = \App\Models\Department::find($admin->department_id)->code;
                    $bookreqs = BookRequest::ofDepartmentCode($code)->orderBy('id', 'desc')->paginate(20, ['book_request.*']);
                    break;
                case "EDITOR": // unknown
                    break;
                default: // error
                    break;
            }
        }

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

    public function reject($id, Request $request){
        $bookreq = BookRequest::find($id);
        if($bookreq->status == 0){
            $bookreq->status = 2;
            if($request->message){
                $js = json_decode($bookreq->message, true);
                $js["admin_reply"] = $request->message;
                $bookreq->message = json_encode($js);
            }
            $bookreq->update();

	        $user      = $bookreq->user;
	        $user_json = $bookreq->user->json_content;
	        $user_json = json_decode($user_json,true);
	        $user_json['teacher']['book_limit'] ++ ;
	        $user->json_content = json_encode($user_json);
	        $user->save();

	        Session::flash('success', '您拒绝了一项样书申请');
        }
        else
            Session::flash('warning', '此申请已经被审批过');

        return redirect()->route("admin.bookreq.index");
    }

    public function destroy($id){
        $req = BookRequest::find($id);
        $req->delete();

        if($req->status==0 ||$req->status == 1) { // 只有在样书申请等待审核或者已经通过的状态下，删除才会在申请限额上减一
	        $bookreq = $req;
	        $user = $bookreq->user;
	        $user_json = $bookreq->user->json_content;
	        $user_json = json_decode($user_json, true);
	        $user_json['teacher']['book_limit']++;
	        $user->json_content = json_encode($user_json);
	        $user->save();
        }
	    Session::flash('success', '您删除了一个样书申请');
        
        return redirect()->route('bookreq.index');
    }
    
    public function shipping($id, Request $request){
        $req = BookRequest::find($id);
        $order_number = $request->order_number;
        if($req->status == 1){
            $req->order_number = $order_number;
            $req->update();
            Session::flash('success', '成功绑定订单号');
        }
        else{
            Session::flash('warning', '此样书申请无法绑定订单号，请检查');
        }

        return redirect()->route('admin.bookreq.show', $id);
    }
    
}
