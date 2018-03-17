<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 2018/3/12
 * Time: 上午12:37
 */

namespace App\Libraries\WechatModules;

use App\Libraries\WechatHandler;
use EasyWeChat\Message\News;
use Illuminate\Support\Facades\Log;

class Test extends WechatHandler {
	public function handle(){

		$openid   = $this->message->FromUserName;

		if ($this->canHandle()){
			$book_url        = url('/order_fb')."?openid=$openid";
			if($this->canHandle()){
				$news = new News([
					'title'       => '订单反馈',
					'description' => "点此查看订单反馈",
					'url'         => $book_url,
					'image'       => route('image',['src'=>'public/book.png']),
				]);
				Log::info("处理模块: Test");
				return $news;
			}
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
		if(($this->message->MsgType == 'text' && strstr($this->message->Content,'订单反馈')))
			return true;
		return false;
	}

	public function name(){
		return '测试模块';
	}
	public function weight(){
		return 10000;
	}

}
