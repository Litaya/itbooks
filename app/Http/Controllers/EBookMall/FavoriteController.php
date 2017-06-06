<?php

namespace App\Http\Controllers\EBookMall;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Favorite;
use App\Models\Book;

use App\Helpers\CrossDomainHelper;

class FavoriteController extends Controller
{

    public function show()
    {
      $user=Auth::user();
      $books=Favorite::where('user_id',$user->id)
                      ->join('book','book.id','=','favorite.target_id')
                      ->get();
      self::grabImages($books);
      return view("Favorite.manage",["favorite_books"=>$books]);
    }

    public function store(Request $request,$bookid)
    {
      $user=Auth::user();
      $oldfavorite=Favorite::where('user_id', $user->id)->where('target_id',$bookid)->where('target_type',3)->get();
      if(empty($oldfavorite[0]))
      {
        $newfavorite =new Favorite;

        $newfavorite->user_id=$user->id;
        $newfavorite->target_id=$bookid;
        $newfavorite->target_type=3;//暂定是图书

        $newfavorite->save();
        return redirect()->back()->with('status','收藏成功！');
      }
      else {
          return redirect()->back()->with('status','已收藏过该图书');
      }

    }

    public function drop($bookid){
      $user=Auth::user();
      Favorite::where('user_id', $user->id)->where('target_id',$bookid)->delete();

      return redirect()->back()->with('status','删除成功！');
    }

    private function grabImages(&$books){
  		for($i=0;$i<count($books);$i++) {
  			if(empty($books[$i]->img_upload)){
  				$books[$i]->img_upload = asset('test_images/book_empty.jpg');
  				$imurl = "http://www.tup.com.cn/upload/bigbookimg/".$books[$i]->product_number.".jpg";
  				if(CrossDomainHelper::url_exists($imurl, $imurl)){ $books[$i]->img_upload = $imurl; $info_changed = true; }
  			}
  		}
  	}
}
