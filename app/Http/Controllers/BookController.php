<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Helpers\CrossDomainHelper;
use App\Helpers\RecommendHelper;
use Auth;
use Session;

class BookController extends Controller
{

    public function index()
    {
        $top_books = RecommendHelper::getManuallySetTopBook();
        $books_recommend = RecommendHelper::getBookRecommend(Auth::user());
        $hot_books = RecommendHelper::getHotBooks();
        $new_books = RecommendHelper::getNewBooks();

        self::grabImages($top_books);
        self::grabImages($books_recommend);
        self::grabImages($hot_books);
        self::grabImages($new_books);
        
        return  view("book.index")
                ->withTopbooks($top_books)
                ->withBooksrecommend($books_recommend)
                ->withHotbooks($hot_books)
                ->withNewbooks($new_books);
    }

    // GET
    public function show($id){
        $book = Book::find($id);

        $info_changed = false;

        if(empty($book->img_upload)){
            $imurl = "http://www.tup.com.cn/upload/bigbookimg/".$book->product_number.".jpg";
            if(CrossDomainHelper::url_exists($imurl, $imurl)){ $book->img_upload = $imurl; $info_changed = true; }
        }

        if($book->type==1 && empty($book->kj_url)){
            $kj_url_list = ["http://www.tup.com.cn/upload/books/kj/".$book->product_number.".rar",
                        "http://www.tup.com.cn/upload/books/kj/".$book->product_number.".zip"];
            foreach($kj_url_list as $kj_url)
                if(CrossDomainHelper::url_exists($kj_url, $real_url)){ $book->kj_url = $real_url; $info_changed = true; break; }
        }

        // if($info_changed) $book->update(); // got error 'Driver [mysql] is not supported'

        if(!empty($book->kj_url)) $book->kj_url = route("navigate", ["url"=>$book->kj_url]);
        return view("book.show")->withBook($book);
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

    // getSearch
    public function search(Request $request){
        $search = $request->search;
        return "search utility not ready";
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
