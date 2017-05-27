<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WechatImgUrl extends Model
{
    protected $table = 'wechat_img_url';
    public $timestamps = false;
    protected $fillable = ['thumb_media_id','url','local_url'];
}
