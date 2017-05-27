<?php

namespace App\Http\Controllers\EBookMall;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\UserInfo;
use App\Models\Comment;
use Auth;

class CommentController extends Controller
{
    //
    public function show($bookid){
      $comments =Comment::where("target_id",$bookid)->where("status",1)->get();
      return view("comment.show",["comments"=>$comments]);
    }

    public function create($bookid){

      $book=Book::find($bookid);

      return view("comment.create",["book"=>$book]);
    }

    public function store(Request $request,$bookid)
    {
      //需要修改
      $user=Auth::user();
      
      $newcomment=new Comment;
      $newcomment->user_id=$user->id;
      $newcomment->target_type=3;
      $newcomment->comment_type=1;
      $newcomment->target_id=$bookid;
      $newcomment->reply_id=0;
      $newcomment->content=$request->content;
      $newcomment->status=1;

      $newcomment->save();
      return redirect()->back();

    }
}
