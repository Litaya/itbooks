<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
	protected $table = 'book';

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
}
