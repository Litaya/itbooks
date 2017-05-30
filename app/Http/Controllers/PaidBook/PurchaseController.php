<?php

namespace App\Http\Controllers\PaidBook;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        return view('paid_book.purchase')->with('user_id',$user_id);
    }
}
