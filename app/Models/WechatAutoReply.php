<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WechatAutoReply extends Model
{
    protected $table = 'wechat_auto_reply';

    protected $fillable = [
    	'id',
	    'regex',
	    'reply'
    ];

    public $timestamps = false;
}
