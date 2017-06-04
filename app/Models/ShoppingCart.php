<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $table = 'shoppingcart';

    protected $fillable = ['id','user_id','isbn','buy_time'];
}
