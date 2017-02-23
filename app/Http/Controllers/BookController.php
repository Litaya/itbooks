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

}
