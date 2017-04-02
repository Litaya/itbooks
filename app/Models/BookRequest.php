<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
	protected $table = 'book_request';

	protected $fillable = [
		'book_id',
		'user_id',
		'shipping_id',
		'status',
		'message',
		'address',
		'phone',
		'receiver',
		'book_type',
		'order_number',
	];

	public function user(){
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}

	public function book(){
		return $this->belongsTo('App\Models\Book', 'book_id', 'id');
	}
}
