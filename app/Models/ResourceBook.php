<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceBook extends Model
{
	protected $table = 'resource_book';

	protected $fillable = [
		'resource_id',
		'book_id'
	];

	public function resource(){
		return $this->belongsTo('App\Models\Resource', 'resource_id', 'id');
	}

	public function book(){
		return $this->belongsTo('App\Models\Book', 'book_id', 'id');
	}

}
