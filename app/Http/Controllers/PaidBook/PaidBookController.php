<?php

namespace App\Http\Controllers\PaidBook;

use DB;
use App\Http\Controllers\Controller;
use App\Models\PaidBook;
use Illuminate\Support\Facades\Auth;

class PaidBookController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $paidbook = PaidBook::where('user_id',$user_id)
                    ->join('book','book.isbn','=','paidbook.isbn')
                    ->get();
        //return $paidbook;
        return view('paid_book.paid_book')->with('paidbook',$paidbook);
    }
}