<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 14/04/2017
 * Time: 12:20 PM
 */

namespace  App\Libraries\WechatModules;

use App\Libraries\WechatHandler;
use EasyWeChat\Message\News;
use Illuminate\Support\Facades\Log;

class Certificate extends WechatHandler {
	public function handle()
	{
		$openid   = $this->message->FromUserName;
		if($this->canHandle()){
			$certificate_url = url('/cert/create')."?openid=$openid";
			$news = new News([
				'title'       => '身份认证',
				'description' => "点此认证您的身份",
				'url'         => $certificate_url,
				'image'       => route('image',['src'=>'public/cert.png']),
			]);
            Log::info('处理模块: Certificate');
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
		$msg_type = $this->message->MsgType;
		$event    = $this->message->Event;
		$eventKey = $this->message->EventKey;

		if($msg_type == 'event' &&  $event== 'CLICK' && $eventKey=='certificate'){
			return true;
		}
		return false;
	}

	public function name()
	{
		return "用户身份认证";
	}
	public function weight()
	{
		return 1;
	}
}
