<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WechatNews extends Model
{
    protected $table = "wechat_news";
    protected $fillable = [
    	'id',
	    'title',
	    'desc',
	    'url',
	    'image'
    ];
}
