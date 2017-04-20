<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WechatAutoReply extends Model
{
    protected $table = 'wechat_auto_reply';

    protected $fillable = [
    	'id',
	    'regex',   # 要匹配的正则表达式
	    'type',    # 要回复的类型 0: 文字, 1: 图片, 2: 图文
	    'content', # 如果是文字，存储文字内容； 如果是图片，存储图片地址； 如果是回复图文消息，则此处存储json数组，存储图文消息的id， [1,2]；
	    'trigger_quantity' # 触发次数
    ];
}
