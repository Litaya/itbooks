<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use App\Models\Book;
use App\Models\Department;

use App\Helpers\FileHelper;
use App\Helpers\CrossDomainHelper;
use Faker\Factory as Faker;

use Auth;
use Image;

use app\Libraries\PermissionManager as PM;

class BookAdminController extends Controller
{
    private $department_array = null;

    public function __construct(){
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $adminrole = PM::getAdminRole();

        if($request->search){
            $search = $request->search;
            switch($adminrole){
                case "SUPERADMIN":
                $books = Book::where('book.ISBN', 'like', "%$search%")
                        ->orWhere('book.name', 'like', "%$search%")
                        ->orWhere('book.authors', 'like', "%$search%");
                break;
                case "DEPTADMIN":
                $code = PM::getAdminDepartmentCode();
                $books = Book::ofDepartmentCode($code)
                        ->where('book.ISBN', 'like', "%$search%")
                        ->orWhere('book.name', 'like', "%$search%")
                        ->orWhere('book.authors', 'like', "%$search%");
                break;
            }
        }
        else{
            switch($adminrole){
                case "SUPERADMIN":
                    $books = Book::query();
                    break;
                case "DEPTADMIN":
                    $code = PM::getAdminDepartmentCode();
                    $books = Book::ofDepartmentCode($code);
                    break;
            }
        }

        if(!empty($request->orderby) and in_array($request->orderby, ["id", "authors", "name", "isbn", "type"])){
            $orderby = $request->orderby;
            $asc = $request->asc == "true" ? "asc" : "desc";
            $books = $books->orderBy($orderby, $asc);
        }
        else{
            $books = $books->orderBy('id', 'desc');
        }
        
        $books = $books->paginate(20);
        return view("admin.book.index")->withBooks($books)->withInput($request->except("page"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.book.create")->withDepartments($this->getDepartmentArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "isbn"=>"required|digits_between:9,13|unique:book,isbn",
            "name"=>"required|min:0|max:100",
            "price"=>"required|numeric",
            "department_id"=>"required",
            "product_number"=>"required",
            "editor_name"=>"required",
            "authors"=>"required",
            "type"=>"in:0,1,2",
            "publish_time"=>"nullable|date",
            "img_upload"=>"nullable",
            "weight"=>"integer|min:0",
        ]);

        $book = new Book;
        $book->isbn = $request->isbn;
        $book->name = $request->name;
        $book->price = $request->price;
        $book->department_id = $request->department_id;
        $book->product_number = $request->product_number;
        $book->editor_name = $request->editor_name;
        $book->authors = $request->authors;
        $book->type = $request->type;
        $book->publish_time = empty($request->publish_time) ? null : $request->publish_time;
        $book->weight = empty($request->weight) ? 0 : $request->weight;
        $book->kj_url = null;
        $kj_url_list = ["http://www.tup.com.cn/upload/books/kj/".$request->product_number.".rar",
                        "http://www.tup.com.cn/upload/books/kj/".$request->product_number.".zip"];
        $real_url = null;
        foreach($kj_url_list as $kj_url)
            if(CrossDomainHelper::url_exists($kj_url, $real_url)){ $book->kj_url = $real_url; break; }


        $book->save();  // $book->id is first set here!!

        // FileHelper::saveBookImage() make use of $book->id so it has to be update() afterwards
        // $book = Book::find($book->id);  // to replace the dirty $book ?
        $book->img_upload = empty($request->img_upload) ? null :
                            FileHelper::saveBookImage($book, $request->img_upload);
        $book->update();

        Session::flash('success', '添加图书成功');

        return redirect()->route("admin.book.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::find($id);
        if(empty($book->img_upload)){
            $imurl = "http://www.tup.com.cn/upload/bigbookimg/".$book->product_number.".jpg";
            if(CrossDomainHelper::url_exists($imurl, $imurl)){ $book->img_upload = $imurl; }
        }

        if(empty($book->kj_url)){
            $kj_url_list = ["http://www.tup.com.cn/upload/books/kj/".$book->product_number.".rar",
                        "http://www.tup.com.cn/upload/books/kj/".$book->product_number.".zip"];
            foreach($kj_url_list as $kj_url) 
                if(CrossDomainHelper::url_exists($kj_url, $real_url)){ $book->kj_url = $real_url; break; }
        }
        
        return view("admin.book.show")->withBook($book);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::find($id);
        return view("admin.book.edit")->withBook($book)->withDepartments($this->getDepartmentArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "isbn"=>"required|digits_between:9,13",
            "name"=>"required|min:0|max:100",
            "price"=>"required|numeric",
            "department_id"=>"required",
            "product_number"=>"required",
            "editor_name"=>"required",
            "authors"=>"required",
            "type"=>"in:0,1,2",
            "publish_time"=>"nullable|date",
            "weight"=>"integer|min:0",
        ]);

        $book = Book::find($id);
        if($book->isbn != $request->isbn)
            $this->validate($request, ["isbn"=>"unique:book,isbn"]);
        
        $book->isbn = $request->isbn;
        $book->name = $request->name;
        $book->price = $request->price;
        $book->department_id = $request->department_id;
        $book->product_number = $request->product_number;
        $book->editor_name = $request->editor_name;
        $book->authors = $request->authors;
        $book->type = $request->type;
        $book->publish_time = empty($request->publish_time) ? null : $request->publish_time;
        $book->kj_url = null;
        $book->weight = empty($request->weight) ? 0 : $request->weight;
        // $kj_url_list = ["http://www.tup.com.cn/upload/books/kj/".$request->product_number.".rar",
        //                 "http://www.tup.com.cn/upload/books/kj/".$request->product_number.".zip"];
        // $real_url = null;
        // foreach($kj_url_list as $kj_url)
        //     if(CrossDomainHelper::url_exists($kj_url, $real_url)){ $book->kj_url = $real_url; break; }
        
        if($request->img_upload) {
            // FileHelper::removeBookImage($book, $book->img_upload); // TODO: implement this
            $book->img_upload = FileHelper::saveBookImage($book, $request->img_upload);
        }
        $book->update();

        Session::flash('success', '成功保存图书信息');
        return redirect()->route("admin.book.show", $book->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        $book->delete();

        Session::flash('success', '成功移除图书');
        return redirect()->route('admin.book.index');
    }

    public function updateKejian($id){
        $book = Book::find($id);
        $kj_url_list = ["http://www.tup.com.cn/upload/books/kj/".$book->product_number.".rar",
                        "http://www.tup.com.cn/upload/books/kj/".$book->product_number.".zip"];
        $real_url = null;
        $old_url = $book->kj_url;
        foreach($kj_url_list as $kj_url) if(CrossDomainHelper::url_exists($kj_url, $real_url)){ $book->kj_url = $real_url; break; }
        if($book->kj_url != $old_url) {  
            $book->update();
            Session::flash('success', '找到并更新了配套课件');
        }
        elseif($real_url === null)
            Session::flash('warning', '社网上找不到本书的配套课件');
        elseif($real_url == $old_url)
            Session::flash('warning', '当前课件链接已经是最新的');

        return 'success';
    }

    private function loadDepartmentArray(){
        $this->department_array = array();
        $departments = Department::all();
        foreach($departments as $dep){
            $this->department_array[$dep->id] = $dep->name;
        }
    }

    private function getDepartmentArray(){
        if($this->department_array === null){
            $this->loadDepartmentArray();
        }
        return $this->department_array;
    }
}
