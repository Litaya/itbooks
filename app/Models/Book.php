<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
	use Searchable;

	protected $table = 'book';

	public function searchableAs(){
		return 'book_index';
	}

	protected $fillable = [
		'isbn',
		'name',
		'price',
		'department_id',
		'product_number',
		'editor_id',
		'editor_name',
		'authors',
		'type',
		'publish_time'
	];

	public function department(){
		return $this->belongsTo('App\Models\Department', 'department_id', 'id');
	}

	public function scopeOfDepartmentCode($query, $code){
		return $query->leftJoin('department', 'department.id', '=', 'book.department_id')
					 ->whereRaw('department.code like \''.$code.'%\'')->select('book.*');
	}
	// what about editor ?
}
