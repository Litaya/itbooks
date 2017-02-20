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
		'receiver'
	];
}
