<?php

namespace App\Http\Controllers\EBookMall;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Favorite;
use App\Models\Book;


class FavoriteController extends Controller
{

    public function show()
    {
      $user=Auth::user();

      return view("Favorite.show",["user"=>$user]);
    }

    public function store(Request $request,$bookid)
    {
      $user=Auth::user();
      $newfavorite =new Favorite;

      $newfavorite->user_id=$user->id;
      $newfavorite->target_id=$bookid;
      $newfavorite->target_type=3;//暂定是图书

      $newfavorite->save();

      //return redirect()->back();
      //将来做弹出消息
    }
}
