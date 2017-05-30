<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookPop;

class BookPopController extends Controller
{
    public function BookPop()
    {
      $books = BookPop::searchSale()->paginate(10);
      return view("book_pop.show")->withBooks($books);
    }
}
