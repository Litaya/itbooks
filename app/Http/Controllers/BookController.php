<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Helpers\CrossDomainHelper;
use App\Helpers\RecommendHelper;
use Auth;

class BookController extends Controller
{

    public function index()
    {
        $books = RecommendHelper::getBookRecommend(Auth::user());
        return view("book.index")->withBooks($books);
    }

    // GET
    public function show($id){
        return view("book.show")->withBook(Book::find($id));
    }

    /*
     * for api
     */
    public function getBooksBySearch(Request $request,$search_string){
	    $books = Book::where('isbn','like',"%$search_string%")
		    ->orWhere('name','like',"%$search_string%")
		    ->orWhere("authors","like","%$search_string%")->where('type','1')->paginate(5);
	    return \GuzzleHttp\json_encode($books);
    }

    public function updateKejian($id){

        if(!Auth::check()){
            return 'denied';
        }

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
}
