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

	static public function getDepartmentType($department_code){
		$type = 0;
		switch (strlen($department_code)){
			case 1:
				$type = 1;
				break;
			case 3:
				$type = 2;
				break;
			case 5:
				$type = 3;
				break;
			default:
				break;
		}
		return $type;
	}
}
