<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WechatModuleModel extends Model
{
    protected $table = 'wechat_module';
    protected $fillable = [
    	'id',
	    'name',
	    'module',
	    'weight'
    ];
}
