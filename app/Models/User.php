<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
	use Notifiable;

	protected $table = 'user';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'openid',
		'username',
		'gender',
		'headimgurl',
		'email',
		'email_status',
		'password',
		'permission_string',
		'certificate_as',
		'information_id',
		'credits',
		'subscribed',
		'source'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/** 申请记录
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function bookRequests(){
		return $this->hasMany('App\Models\BookRequest', 'user_id', 'id');
	}
	/** 评论记录
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function comments(){
		return $this->hasMany('App\Models\Comment','user_id','id');
	}
	/** 在别人回复中被提及的
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function mentioned_comments(){
		return $this->hasMany('App\Models\Comment','reply_id','id');
	}
	/** 收藏记录
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function favorites(){
		return $this->hasMany('App\Models\Favorite','user_id','id');
	}

	public function addDepartmentAdmin(){}

	public function addRepresentative(){}

	public function userInfo(){
		return $this->hasOne('App\Models\UserInfo','user_id','id');
	}

	public function scopeNonAdmin($query){
		return $query->whereRaw('( LENGTH(permission_string) = 0 )');
	}
}
