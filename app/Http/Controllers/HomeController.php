<?php

namespace App\Http\Controllers;

use App\Helpers\CrossDomainHelper;
use App\Helpers\RecommendHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->middleware('wechat.auth');
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $top_books = RecommendHelper::getManuallySetTopBook();
	    $books_recommend = RecommendHelper::getBookRecommend(Auth::user());
	    $hot_books = RecommendHelper::getHotBooks();
	    $new_books = RecommendHelper::getNewBooks();

//	    self::grabImages($top_books);
//	    self::grabImages($books_recommend);
//	    self::grabImages($hot_books);
//	    self::grabImages($new_books);
//
	    return  view("home")->withTopbooks($top_books)
		    ->withBooksrecommend($books_recommend)
		    ->withHotbooks($hot_books)
		    ->withNewbooks($new_books);
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
