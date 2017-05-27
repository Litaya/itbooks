<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';

    protected $fillable = [
        'id',
	    'name',
	    'user_id'
    ];

    public function materials()
    {
	    return $this->hasMany('App\Models\Material', "category_id", "id");
    }
}