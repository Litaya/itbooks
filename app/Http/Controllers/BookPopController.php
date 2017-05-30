<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookPopController extends Controller
{
    public function BookPop()
    {
      return view("book_pop.show");
    }
}
