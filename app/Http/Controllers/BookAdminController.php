<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use App\Models\Book;
use App\Models\Department;

use App\Helpers\FileHelper;
use Faker\Factory as Faker;

use Image;

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
    public function index()
    {
        $books = Book::paginate(20);
        return view("admin.book.index")->withBooks($books);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.book.create")->withFaked($book)->withDepartments($this->getDepartmentArray());
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
        $book->save();  // $book->id is first set here!!

        // FileHelper::saveBookImage() make use of $book->id
        // so it has to be update() afterwards!!
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
        if($request->img_upload)
        {
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
