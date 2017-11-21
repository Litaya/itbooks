<?php

namespace App\Http\Controllers;

use App\Dao\BookRequestDao;
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
                                            ->whereRaw(
                                                "((user.username like '%$search%')
                                                  or (book_request.receiver like '%$search%')
                                                  or (book.name like '%$search%')
                                                  or (book.isbn like '%$search%'))")
                                            ->select('book_request.*');
                    break;

                case "DEPTADMIN":
                    $code = $spec_code;
                    $bookreqs = BookRequest::ofDepartmentCode($code)
                                            //->join('book', 'book_request.book_id', '=', 'book.id')
                                            ->join('user', 'book_request.user_id', '=', 'user.id')
                                            ->whereRaw(
                                                "((user.username like '%$search%')
                                                  or (book_request.receiver like '%$search%')
                                                  or (book.name like '%$search%')
                                                  or (book.isbn like '%$search%'))")
                                            ->select('book_request.*');
                    break;
                
                case "EDITOR": // currently unknown
                    break;
                
                case "REPRESENTATIVE":
                    $prov_id = $spec_code;
                    $bookreqs = BookRequest::ofDistrict($prov_id)
                                            ->join('book', 'book_request.book_id', '=', 'book.id')
                                            ->join('user', 'book_request.user_id', '=', 'user.id')
                                            ->whereRaw(
                                                "((user.username like '%$search%')
                                                  or (book_request.receiver like '%$search%')
                                                  or (book.name like '%$search%')
                                                  or (book.isbn like '%$search%'))")
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
                $req_builder = $req_builder->whereRaw('book_request.status <> 0');
            elseif($request->category == "unhandled")
                $req_builder = $req_builder->whereRaw('book_request.status = 0');
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

	    $result = BookRequestDao::passBookRequest($bookreq, Auth::user(), $bookreq->user);
        Session::flash($result["status"]==BookRequestDao::$SUCCESS?"success":"danger", $result["message"]);

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
	    $reply_message = $request->message?$request->message:"";
	    $result = BookRequestDao::rejectBookRequest($bookreq, Auth::user(), $bookreq->user, $reply_message);
	    Session::flash($result["status"]==BookRequestDao::$SUCCESS?"success":"danger", $result["message"]);

        $args = [];
        if(!empty($request->category)) $args["category"] = $request->category;
        if(!empty($request->search)) $args["search"] = $request->search;
        if(!empty($request->page)) $args["page"] = $request->page;

        return redirect()->route("admin.bookreq.index", $args);
    }

    public function destroy(Request $request, $id){
        $req = BookRequest::find($id);

        $result = BookRequestDao::destroyBookRequest($req, $req->user);
	    Session::flash($result["status"]==BookRequestDao::$SUCCESS?"success":"danger", $result["message"]);

        $args = [];
        if(!empty($request->category)) $args["category"] = $request->category;
        if(!empty($request->search)) $args["search"] = $request->search;
        if(!empty($request->page)) $args["page"] = $request->page;

        return redirect()->route("admin.bookreq.index", $args);
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

        $args = [];
        if(!empty($request->category)) $args["category"] = $request->category;
        if(!empty($request->search)) $args["search"] = $request->search;
        if(!empty($request->page)) $args["page"] = $request->page;

        return redirect()->route("admin.bookreq.index", $args);
    }


    public function resetStatus($id, Request $request){
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

	    $result = BookRequestDao::resetBookRequest($bookreq, Auth::user(), $bookreq->user);
	    Session::flash($result["status"]==BookRequestDao::$SUCCESS?"success":"danger", $result["message"]);

	    $args = [];
        if(!empty($request->category)) $args["category"] = $request->category;
        if(!empty($request->search)) $args["search"] = $request->search;
        if(!empty($request->page)) $args["page"] = $request->page;

        return redirect()->route("admin.bookreq.index", $args);

    }
}
