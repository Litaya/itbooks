<?php

namespace App\Libraries\WechatModules;

use App\Libraries\WechatTextHandler;
use App\Models\WechatAutoReply;
use App\Models\WechatNews;
use EasyWeChat\Message\News;
use Illuminate\Support\Facades\Log;

class AutoReply extends WechatTextHandler{

	public function handle($openid,$message)
	{
		$auto_replies = WechatAutoReply::all();
		$matched      = false;
		$reply        = "";

		foreach ($auto_replies as $auto_reply){
			if(preg_match("/$auto_reply->regex/",$message)){
				$matched  = true;
				$rep_type = $auto_reply->type;
				$content  = $auto_reply->content;
				switch ($rep_type){
					case 0: # 文字
					case 1: # 图片
						$reply = $content;
						break;
					case 2: # 图文
						$reply = $this->getNews($openid,$content);
						break;
					default:
						$reply = "";
						break;
				}
				break;
			}
		}

		# 本模块能处理的情况下，不考虑其他模块
		if($matched){
			return $reply;
		}

		# 责任链没有断的情况下，继续向下处理
		if(!empty($this->successor)){
			return $this->successor->handle($openid,$message);
		}else{ # 没有下一个处理模块，则返回空串
			return "";
		}
	}

	public function weight()
	{
		return 10;
	}

	public function name()
	{
		return 'AutoReply';
	}

	/**
	 * @param $content string Json数组，需要解析为news_ids
	 * @return array
	 */
	private function getNews($openid,$content){
		$news_ids = json_decode($content,true);
		$news = [];
		foreach ($news_ids as $news_id){
			$wechatNews = WechatNews::where('id',$news_id)->first();
			$new  = new News([
				'title'       => $wechatNews->title,
				'description' => $wechatNews->desc,
				'url'         => url($wechatNews->url)."?openid=$openid",
				'image'       => $wechatNews->image,
			]);
			array_push($news,$new);
		}
		return $news;
	}
}
