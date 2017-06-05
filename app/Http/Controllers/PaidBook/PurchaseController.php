<?php

namespace App\Http\Controllers\PaidBook;

use DB;
use Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ShoppingCart;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        return view('paid_book.purchase')->with('user_id',$user_id);
    }

    public function shopping_cart()
    {
        $user_id = Auth::user()->id;
        $cart = ShoppingCart::where('user_id',$user_id)
                    ->join('book','book.isbn','=','shoppingcart.isbn')
                    ->orderBy('add_time','desc')
                    ->get();
        return view('paid_book.shopping_cart')->with('cart',$cart);
    }

    public function add_cart($bookisbn)
    {
        $user_id = Auth::user()->id;
        $cart = ShoppingCart::firstOrNew(
            ['user_id' => $user_id], ['isbn' => $bookisbn]
        );
        $cart->add_time = Carbon\Carbon::now();
        $cart->save();
    }

    public function drop_cart($bookisbn)
    {
        $user_id = Auth::user()->id;
        ShoppingCart::where('user_id',$user_id)
        ->where('isbn',$bookisbn)
        ->delete();

        return redirect()->back()->withInput(['删除成功！']);
    }
}
