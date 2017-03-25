<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materials';

    protected $fillable = [
    	'id',
	    'title',
	    'media_id',
	    'thumb_media_id',
	    'show_cover_pic',
	    'author',
	    'digest',
	    'url',
	    'content_source_url',
	    'reading_quantity',
	    'category_id',
	    'wechat_update_time',
    ];

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
