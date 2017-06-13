<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountBalance extends Model
{
    protected $table = 'account_balance';
    protected $primaryKey = 'user_id';

    protected $fillable = ['user_id','balance'];
}
