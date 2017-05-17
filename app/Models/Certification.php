<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
	protected $table = 'certification';

	protected $fillable = [
		'cert_name', // TEACHER|AUTHOR|STUDENT|EDITOR|DEPARTMENT_ADMIN|SUPER_ADMIN
		'user_id',
		'cert_content',
		'status',
		'message'
	];

	public function user(){
		return $this->belongsTo("App\Models\User", "user_id", "id");
	}
}
