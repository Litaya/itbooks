<?php

namespace App\Libraries\WechatModules;

use App\Libraries\WechatTextHandler;
use App\Models\WechatAutoReply;


class AutoReply extends WechatTextHandler{

	public function handle($message)
	{
		$auto_replies = WechatAutoReply::all();
		$matched      = false;
		$reply        = "";
		foreach ($auto_replies as $auto_reply){
			if(preg_match($auto_reply->regex,$message)){
				$matched = true;
				$reply   = $auto_reply->reply;
				break;
			}
		}

		# 本模块能处理的情况下，不考虑其他模块
		if($matched){
			return $reply;
		}

		# 责任链没有断的情况下，继续向下处理
		if(!empty($this->successor)){
			return $this->successor->handle($message);
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
}