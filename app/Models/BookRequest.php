<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
	protected $table = 'book_request';

	public $timestamps = false;  // here is a problem

	protected $fillable = [
		'book_id',
		'user_id',
		'shipping_id',
		'status',
		'message',
		'address',
		'phone',
		'receiver'
	];

	public function user(){
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}

	public function book(){
		return $this->belongsTo('App\Models\Book', 'book_id', 'id');
	}
}
