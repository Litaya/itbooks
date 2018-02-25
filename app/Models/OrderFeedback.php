<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderFeedback extends Model
{
	protected $table    = 'order_feedback';

	protected $fillable = [
		'id',
		'book_id',
		'book_isbn',
		'department_id',
		'department_name',
		'user_id',
		'user_realname',
		'admin_id',
		'admin_name',
		'order_time',
		'order_count',
		'image_path',
		'status',
		'refuse_message',
		'ext'
	];

	public function user(){
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}
	public function book(){
		return $this->belongsTo('App\Models\Book', 'book_id', 'id');
	}
	public function admin(){
		return $this->belongsTo('App\Models\User', 'admin_id', 'id');
	}

}
