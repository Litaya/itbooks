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
        'cover_path',
	    'show_cover_pic',
	    'author',
	    'digest',
	    'content',
	    'url',
	    'content_source_url',
	    'reading_quantity',
	    'category_id',
	    'wechat_update_time',
    ];

    public $timestamps = false;

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

	// 搜索文章内容
	public static function search($message){
		$materials = self::where('title','like',"%$message%")
			->orWhere('author','like',"%$message%")
			->orWhere('digest','like',"%$message%")
			->orderBy('wechat_update_time','desc');
		return $materials;
	}

	public static function lists(){
		return self::orderBy('wechat_update_time','desc')->simplePaginate(10);
	}
}
