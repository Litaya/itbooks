<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Session;
use Illuminate\Http\Request;

class ReadController extends Controller
{

    // 某书有多少人读过 $book_id = $request->book_id
    public function getBookReadCount(Request $request){
        $book_id = $request->book_id;
        $count = DB::select('select count(*) brcount from user_read_book where book_id = ?', [book_id])[0]['brcount'];
        return $count;
    }

    // 用户点击“读过”按钮 $book_id = $request->book_id
    public function read(Request $request){
        $this->validate($request, [
            "book_id" => "required",
        ]);

        $book_id = $request->book_id;
        $user_id = Auth::id();

        $old_entry = DB::select('select id from user_read_book where user_id = ? and book_id = ?', [$user_id, $book_id]);
        
        DB::insert('insert into user_read_book (user_id, book_id) values (?, ?)', [$user_id, $book_id]);
        
        return 'success';
    }

    public function unread(Request $request){
        $this->validate($request, [
            "book_id" => "required"
        ]);

        $book_id = $request->book_id;
        $user_id = Auth::id();

        DB::delete('delete from user_read_book where user_id = ? and book_id = ?', [$user_id, $book_id]);

        return 'success';
    }
}
