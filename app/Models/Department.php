<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
	protected $table = 'department';

	public $timestamps = false;

	protected $fillable = [
		'code',
		'name',
		'principal_name',
		'type'
	];
}
