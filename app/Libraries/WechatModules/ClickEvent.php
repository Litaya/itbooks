<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 2018/6/26
 * Time: 下午2:24
 */

namespace App\Libraries\WechatModules;

use App\Libraries\WechatHandler;
use App\Libraries\WechatMessageFactory;

class ClickEvent extends WechatHandler{
	public function handle()
	{
		$match = false;
		$reply = '';
		if ($this->canHandle()){
			$match = true;
			$message_factory  = new WechatMessageFactory();
			$reply = $message_factory->factory($this->message, WechatMessageFactory::$CW_BR_PWD_HINT);
		}

		# 本模块能处理的情况下，不考虑其他模块
		if($match){
			Log::info("处理模块: ClickEvent");
			return $reply;
		}

		# 责任链没有断的情况下，继续向下处理
		if(!empty($this->successor)){
			Log::info('模块[ClickEvent]无法处理，传递给下一个模块');
			return $this->successor->handle();
		}else{ # 没有下一个处理模块，则返回空串
			Log::info('模块[ClickEvent]是最后一个模块');
			return "";
		}
	}

	public function canHandle(){
		if($this->message->MsgType == 'event' && $this->message->Event == 'CLICK' && $this->message->EventKey=='cw_br_pwd') {
			return true;
		}
		return false;
	}

	public function name()
	{
		return '课件样书和密码';
	}
	public function weight()
	{
		return 1;
	}
}