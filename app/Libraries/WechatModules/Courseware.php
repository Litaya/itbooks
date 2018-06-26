<?php

namespace App\Libraries\WechatModules;

use App\Dao\ResourceDao;
use App\Libraries\WechatHandler;
use App\Libraries\WechatMessageFactory;
use App\Models\Book;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Courseware extends WechatHandler{
	public function handle()
	{
		$match = false;
		$reply = '';
		if ($this->canHandle()){
			$msg_type = $this->message->MsgType;
			$match    = true;
			$reply    = "";
			$message_factory  = new WechatMessageFactory();
			if($msg_type == 'text'){
				$reply =  $message_factory->factory($this->message, WechatMessageFactory::$COURSEWARE_REPLY);
			}
			if ($msg_type == 'event'){
				$reply =  $message_factory->factory($this->message, WechatMessageFactory::$COURSEWARE_HINT);
			}
		}

		if($match){
			Log::info("处理模块：Courseware");
			return $reply;
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
		$content = trim($this->message->Content);
		if($msg_type == 'text' && (preg_match("/[^#]+#[0-9]+/",$content) || preg_match("/[^＃]+＃[0-9]+/",$content))){
			return true;
		}
		if($this->message->MsgType == 'event' && $this->message->Event == 'CLICK' && $this->message->EventKey=='courseware') {
			return true;
		}
		return false;
	}

	public function name(){
		return '课件密码';
	}
	public function weight()
	{
		return 10;
	}
}
