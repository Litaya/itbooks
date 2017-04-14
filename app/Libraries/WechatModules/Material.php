<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 14/04/2017
 * Time: 12:32 PM
 */

namespace App\Libraries\WechatModules;

use App\Libraries\WechatHandler;
use EasyWeChat\Message\News;

class Material extends WechatHandler{
	public function handle()
	{
		if($this->canHandle()){
			$openid   = $this->message->FromUserName;
			$material_url    = url('/material')."?openid=$openid";
			$news = new News([
				'title'       => '精彩好文',
				'description' => "点此查看精彩文章",
				'url'         => $material_url,
				'image'       => route('image',['src'=>'public/material.png']),
			]);
			return $news;
		}

		# 责任链没有断的情况下，继续向下处理
		if(!empty($this->successor)){
			return $this->successor->handle();
		}else{ # 没有下一个处理模块，则返回空串
			return "";
		}
	}

	private function canHandle(){
		if(($this->message->MsgType == 'text' && strstr($this->message->Content,'文章列表'))||($this->message->MsgType == 'event' && $this->message->Event == 'click' && $this->message->EventKey=='material'))
			return true;
		return false;
	}

	public function name()
	{
		return '文章列表';
	}
	public function weight()
	{
		return 1;
	}
}