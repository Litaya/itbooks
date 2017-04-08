<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';

    protected $fillable = [
        'id',
	    'name'
    ];

    public function material()
    {
	    return $this->hasMany('App\Models\Material', "target_id", "id");
    }
}