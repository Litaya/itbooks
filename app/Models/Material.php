<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materials';

    public function category(){
    	return $this->belongsTo('App\Models\Category','category_id','id');
    }

    public function labels(){
    	return $this->belongsToMany('App\Models\Label','add_label','target_id','label_id');
    }

	public function comments(){
		return $this->hasMany('App\Models\Comment','target_id','id');
	}

	// 对应的收藏记录
	public function favorites(){
		return $this->hasMany('App\Models\Favorite','target_id','id');
	}
}
