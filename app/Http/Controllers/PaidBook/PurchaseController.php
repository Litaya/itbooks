<?php

namespace App\Http\Controllers\PaidBook;

use DB;
use Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ShoppingCart;
use App\Models\AccountBalance;
use App\Models\PaidBook;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    private $total_price;
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
        $this->total_price = 0.00;
        foreach($cart as $book)
        {
            $this->total_price += $book->price;
        }
        return view('paid_book.shopping_cart')->with('cart',$cart)->with('total_price',$this->total_price);
    }

    public function add_cart($bookisbn)
    {
        $user_id = Auth::user()->id;
        if(ShoppingCart::where('user_id', $user_id)->where('isbn',$bookisbn)->exists() == true)
          return redirect()->back()->with('info','该商品已经在购物车中！');
        $cart = ShoppingCart::firstOrNew(
          array('user_id' => $user_id, 'isbn' => $bookisbn)
        );
        $cart->add_time = Carbon\Carbon::now();
        $cart->save();
        return redirect()->back()->with('info','添加购物车成功！');
    }

    public function drop_cart($bookisbn)
    {
        $user_id = Auth::user()->id;
        ShoppingCart::where('user_id',$user_id)
        ->where('isbn',$bookisbn)
        ->delete();
        return redirect()->back()->withInput(['删除成功！']);
    }

     public function purchase()
     {
         $user_id = Auth::user()->id;
         //账户余额
         $balance = AccountBalance::find($user_id);
         if(AccountBalance::where('user_id',$user_id)->exists() == false)
         {
             $balance = AccountBalance::firstOrNew(
             ['user_id' => $user_id]
             );
             $balance->balance = 0;
             $balance->save();
         }
         //else
         //$balance = AccountBalance::find($user_id);
         //return var_export($balance != null,true);
         if($balance->balance < $this->total_price)
         {
             return redirect()->back()->with('info','余额不足');
         }
         $cart = ShoppingCart::where('user_id',$user_id)->get();
         foreach($cart as $book)
         {
            PaidBook::insert(
              array('user_id'=>$user_id,'isbn'=>$book->isbn)
            );
         }
         ShoppingCart::where('user_id',$user_id)->delete();
         $balance->balance = $balance->balance-$this->total_price;
         $balance->save();
         //return $balance->balance;
         return redirect()->back()->with('info','支付成功，余额为￥'.number_format($balance->balance,2));
     }
}
