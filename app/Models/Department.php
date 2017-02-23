<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
	protected $table = 'department';

	protected $fillable = [
		'code',
		'name',
		'principal_name',
		'type'
	];
}
