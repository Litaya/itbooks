<?php

namespace App\Libraries;

use App\Models\Wechat;
use EasyWeChat\Message\Text;

class WechatMessageSender{
	public static function sendText($openid,$content){
		$app    = Wechat::getInstance()->getApp();
		return $app->staff->message(new Text(['content'=>$content]))->to($openid)->send();
	}

	public static function sendNews($openid, $news){
		$app = Wechat::getInstance()->getApp();
		return $app->staff->message($news)->to($openid)->send();
	}
}