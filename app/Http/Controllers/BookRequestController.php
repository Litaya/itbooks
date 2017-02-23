<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\BookRequest;
use App\Models\Book;

use Illuminate\Support\Facades\Auth;    // to use Auth::id() and Auth::user()
use Illuminate\Support\Facades\Session; // to use Session::get()/set()/flash()

class BookRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = User::find($request->userId);
        return view('book_request.index')->withUser($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($book_id)
    {
        $book = Book::find($book_id);
        return view('book_request.create')->withBook($book);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'book_id' => 'required',
            'address' => 'required|max:255',
            'phone' => 'regex:/\+?[0-9\-]+/',
            'receiver' => 'required|max:10',
            'message' => 'max:255'
        ));

        $req = new BookRequest;
        $req->user_id = Auth::id();
        $req->book_id = $request->book_id;
        $req->address = $request->address;
        $req->phone = $request->phone;
        $req->receiver = $request->receiver;
        $req->message = $request->message;
        $req->status = 0;

        $req->save();

        Session::flash('success', '您的样书申请已经成功提交！');
        
        return redirect()->route('bookreq.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bookreq = BookRequest::find($id);
        return view('book_request.show')->withBookreq($bookreq);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return redirect()->route('bookreq.index'); //样书申请表不提供修改功能
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
        return redirect()->route('bookreq.index'); //样书申请表不提供修改功能
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $req = BookRequest::find($id);
        $bookname = $req->book->name;
        $req->delete();

        Session::flash('success', '您已经取消了对'.$bookname.'的样书申请');
        
        return redirect()->route('bookreq.index');
    }
}
