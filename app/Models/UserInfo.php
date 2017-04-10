<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
	protected $table = 'user_info';
	protected $primaryKey = 'user_id';
	public $incrementing = false;

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
		'department_name',
		'address',
		'json_content',
	];

	public function user(){
		return $this->belongsTo('App\Models\User','user_id','id');
	}

	public function province(){
		return $this->hasOne('App\Models\District', 'id', 'province_id');
	}

	public function city(){
		return $this->hasOne('App\Models\District', 'id', 'city_id');
	}

	public function scopeOfProvince($query, $province_id){
		return $query->where('province_id', $province_id);
	}
}
