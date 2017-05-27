<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaidBook extends Model
{
    protected $table = 'paidbook';

    protected $fillable = ['id','user_id','isbn','buy_time'];
}