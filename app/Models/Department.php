<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
	protected $table = 'user_info';

	protected $fillable = [
		'code',
		'name',
		'principal_name',
		'type'
	];
}
