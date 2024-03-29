<?php

namespace App\Libraries\WechatModules;

use App\Libraries\WechatHandler;
use EasyWeChat\Message\News;
use Illuminate\Support\Facades\Log;

class Book extends WechatHandler{
	public function handle()
	{
		$openid   = $this->message->FromUserName;

		$book_url        = url('/home')."?openid=$openid";
		if($this->canHandle()){
			$news = new News([
				'title'       => '图书资源',
				'description' => "点此查看图书资源",
				'url'         => $book_url,
				'image'       => route('image',['src'=>'public/book.png']),
			]);
            Log::info("处理模块: Book");
			return $news;
		}

		# 责任链没有断的情况下，继续向下处理
		if(!empty($this->successor)){
            Log::info('模块['.$this->name().']无法处理，传递给下一个模块');
			return $this->successor->handle();
		}else{ # 没有下一个处理模块，则返回空串
            Log::info('模块['.$this->name().']是最后一个模块');
			return "";
		}
	}

	private function canHandle(){
		if(($this->message->MsgType == 'text' && strstr($this->message->Content,'图书资源'))||($this->message->MsgType == 'event' && $this->message->Event == 'CLICK' && $this->message->EventKey=='book'))
			return true;
		return false;
	}

	public function name()
	{
		return '图书模块';
	}
	public function weight()
	{
		return 1;
	}
}
