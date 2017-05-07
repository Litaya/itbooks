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

	public function scopeOfDepartmentCode($query, $code){
		return $query->leftJoin('book', 'book.id', '=', 'book_request.book_id')
					 ->leftJoin('department', 'department.id', '=', 'book.department_id')
					 ->whereRaw('(department.code like \''.$code.'%\')')
					 ->select('book_request.*');
	}

	public function scopeOfDistrict($query, $province_id){
		return $query->leftJoin('user', 'user.id', '=', 'book_request.user_id')
					 ->leftJoin('user_info', 'user_info.user_id', '=', 'user.id')
					 ->where('user_info.province_id', $province_id)
					 ->select("book_request.*");
	}

	public function scopeUnhandled($query){
		return $query->where('status', 0);
	}

	public function scopeAcceptedButNotSent($query){
		return $query->where('status', 1)->whereRaw('(isnull(order_number) or LENGTH(order_number) = 0)');
	}

	public function handler(){
		return $this->hasOne('\App\Models\User', 'id', 'handler_id');
	}
}
