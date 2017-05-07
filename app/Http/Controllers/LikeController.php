<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Session;
use Illuminate\Http\Request;

class LikeController extends Controller
{
        // 某书有多少人读过 $book_id = $request->book_id
    public function getBookLikeCount(Request $request){
        $book_id = $request->book_id;
        $count = DB::select('select count(*) blcount from user_like_book where book_id = ?', [$book_id])[0]['blcount'];
        return $count;
    }

    // 用户点击“读过”按钮 $book_id = $request->book_id
    public function like(Request $request){
        $this->validate($request, [
            "book_id" => "required",
        ]);

        $book_id = $request->book_id;
        $user_id = Auth::id();

        DB::insert('insert into user_like_book (user_id, book_id) values (?, ?)', [$user_id, $book_id]);

        return 'success';
    }

    public function unlike(Request $request){
        $this->validate($request, [
            "book_id" => "required"
        ]);

        $book_id = $request->book_id;
        $user_id = Auth::id();

        DB::delete('delete from user_like_book where user_id = ? and book_id = ?', [$user_id, $book_id]);

        return 'success';
    }
}
