<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
	protected $table = 'user_info';

	protected $fillable = [
		'user_id',
		'phone',
		'realname',
		'school_id',
		'school_name',
		'workplace',
		'book_id',
		'district_id',
		'district_name',
		'department_id',
		'department_name'
	];
}
