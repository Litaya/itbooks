<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

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
}
