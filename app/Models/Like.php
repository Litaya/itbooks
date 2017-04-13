<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'user_like_book';

    protected $fillable = [
	    'user_id',
	    'book_id',
    ];

	public function user(){
		$this->belongsTo('App\Models\User','user_id','id');
	}

    public function book(){
        $this->belongsTo('App\Models\Book','book_id','id');
    }
}
