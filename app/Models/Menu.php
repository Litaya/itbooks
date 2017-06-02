<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $fillable = [
    	'id','title','json','status','created_at', 'updated_at'
    ];
}
