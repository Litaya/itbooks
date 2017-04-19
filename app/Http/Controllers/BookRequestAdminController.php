<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\Book;

use App\Models\BookRequest;
use DB;

use app\Libraries\PermissionManager as PM;

class BookRequestAdminController extends Controller
{

    public function __construct(){
        $this->middleware('auth');  // TODO: change to admin later
    }

    public static function getSearchResultBuilder($search, $ar, $spec_code){
        if($search != NULL){
            switch($ar){
                case "SUPERADMIN":
                    $bookreqs = BookRequest::join('user', 'book_request.user_id', '=', 'user.id')
                                            ->join('book', 'book_request.book_id', '=', 'book.id')
                                            ->where('user.username', 'like', "%$search%")
                                            ->orWhere('book.name', 'like', "%$search%")
                                            ->select('book_request.*');
                    break;

                case "DEPTADMIN":
                    $code = $spec_code;
                    $bookreqs = BookRequest::ofDepartmentCode($code)
                                            ->join('book', 'book_request.book_id', '=', 'book.id')
                                            ->join('user', 'book_request.user_id', '=', 'user.id')
                                            ->where('user.username', 'like', "%$search%")
                                            ->orWhere('book.name', 'like', "%$search%")
                                            ->select('book_request.*');
                    break;
                
                case "EDITOR": // currently unknown
                    break;
                
                case "REPRESENTATIVE":
                    $prov_id = $spec_code;
                    $bookreqs = BookRequest::ofDistrict($prov_id)
                                            ->join('book', 'book_request.book_id', '=', 'book.id')
                                            ->join('user', 'book_request.user_id', '=', 'user.id')
                                            ->where('user.username', 'like', "%$search%")
                                            ->orWhere('book.name', 'like', "%$search%")
                                            ->select('book_request.*');
                    break;

                default: // reaching here should cause an error
                    break;
            }

        }
        else {
            switch($ar){
                case "SUPERADMIN":
                    $bookreqs = BookRequest::query();
                    break;
                case "DEPTADMIN":
                    $code = $spec_code;
                    $bookreqs = BookRequest::ofDepartmentCode($code);
                    break;
                case "EDITOR": // unknown
                    break;
                case "REPRESENTATIVE":
                    $prov_id = $spec_code;
                    $bookreqs = BookRequest::ofDistrict($prov_id);
                    break;
                default: // error
                    break;
            }
        }

        return $bookreqs;
    }

    public function getIndex(Request $request){

        $search = $request->get('search');
        $ar = PM::getAdminRole();
        $code = "";
        if($ar == "DEPTADMIN") 
            $code = PM::getAdminDepartmentCode();
        else if($ar == "REPRESENTATIVE") 
            $code = PM::getAdminDistrict();

        $req_builder = self::getSearchResultBuilder($search, $ar, $code);

        if(!empty($request->category)){
            if($request->category == "handled")
                $req_builder = $req_builder->where('book_request.status', '<>', 0);
            else
                $req_builder = $req_builder->where('book_request.status', '=', 0);
        }

        $bookreqs = $req_builder->orderBy('book_request.id', 'desc')->paginate(20);

        return view('admin.book_request.index')->withBookreqs($bookreqs);
    }

    public function show($id){
        $bookreq = BookRequest::find($id);
        return view('admin.book_request.show')->withBookreq($bookreq);
    }

    public function pass($id){
        $bookreq = BookRequest::find($id);
        
        // 权限检查
        if(!in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"]))
            return redirect()->back()->withErrors(["您没有处理样书申请的权限"]);
        if(PM::getAdminRole() == "DEPTADMIN"){
            $book_code = Book::find($bookreq->book_id)->department->code;
            $admin_code = PM::getAdminDepartmentCode();
            if(strpos($book_code, $admin_code) !== 0)
                return redirect()->back()->withErrors(["您没有处理此样书申请的权限"]);
        }
        // 权限检查通过

        if($bookreq->status == 0){
            $bookreq->status = 1;
            $bookreq->handler_id = Auth::id();
            $bookreq->update();
            Session::flash('success', '您通过了一项样书申请');
        }
        else
            Session::flash('warning', '此申请已经被审批过');

        return redirect()->route("admin.bookreq.index");
    }

    public function reject($id, Request $request){
        $bookreq = BookRequest::find($id);

        // 权限检查
        if(!in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"]))
            return redirect()->back()->withErrors(["您没有处理样书申请的权限"]);
        if(PM::getAdminRole() == "DEPTADMIN"){
            $book_code = Book::find($bookreq->book_id)->department->code;
            $admin_code = PM::getAdminDepartmentCode();
            if(strpos($book_code, $admin_code) !== 0)
                return redirect()->back()->withErrors(["您没有处理此样书申请的权限"]);
        }
        // 权限检查通过


        if($bookreq->status == 0){
            $bookreq->status = 2;
            $bookreq->handler_id = Auth::id();
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

        $bookreq = BookRequest::find($id);

        // 权限检查
        if(!in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"]))
            return redirect()->back()->withErrors(["您没有处理样书申请的权限"]);
        if(PM::getAdminRole() == "DEPTADMIN"){
            $book_code = Book::find($bookreq->book_id)->department->code;
            $admin_code = PM::getAdminDepartmentCode();
            if(strpos($book_code, $admin_code) !== 0)
                return redirect()->back()->withErrors(["您没有处理此样书申请的权限"]);
        }
        // 权限检查通过

        $order_number = $request->order_number;
        if($bookreq->status == 1){
            $bookreq->order_number = $order_number;
            $bookreq->update();
            Session::flash('success', '成功绑定订单号');
        }
        else{
            Session::flash('warning', '此样书申请无法绑定订单号，请检查');
        }

        return redirect()->route('admin.bookreq.show', $id);
    }
    

    public function passAndBindOrder($id, Request $request){
        $this->validate($request, [
            "order_number" => "required"
        ]);
        
        $bookreq = BookRequest::find($id);

        // 权限检查
        if(!in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"]))
            return redirect()->back()->withErrors(["您没有处理样书申请的权限"]);
        if(PM::getAdminRole() == "DEPTADMIN"){
            $book_code = Book::find($bookreq->book_id)->department->code;
            $admin_code = PM::getAdminDepartmentCode();
            if(strpos($book_code, $admin_code) !== 0)
                return redirect()->back()->withErrors(["您没有处理此样书申请的权限"]);
        }
        // 权限检查通过

        if($bookreq->status == 0){
            $bookreq->status = 1;
            $bookreq->handler_id = Auth::id();
            $bookreq->order_number = $request->order_number;
            $bookreq->update();
            Session::flash('success', '成功绑定订单号');
        }
        else
            Session::flash('warning', '此申请已经被审批过');

        if(!empty($request->category))
            return redirect()->route("admin.bookreq.index", ["category" => $request->category]);
        return redirect()->route("admin.bookreq.index");
    }
}
