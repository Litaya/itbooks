<?php
namespace App\Libraries\WechatModules;

use App\Libraries\WechatHandler;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class BookReq extends WechatHandler {
	public function handle()
	{
		$openid   = $this->message->FromUserName;

		// 如果有样书
		if ($this->canHandle()){
			$user = User::where('openid',$openid)->first();
			if(!empty($user)&&strpos($user->certificate_as,'TEACHER')!==false){
				$reply = "只有认证的教师才可以申请教材样书（样书会在5个工作日内处理）。\n".
					"<a href='http://www.itshuquan.com/bookreq?openid=".$openid."'>申请教材样书</a>\n".
					"<a href='http://www.itshuquan.com/bookreq/record?openid=".$openid."'>查看样书记录</a>\n".
					"<a href='http://www.itshuquan.com/home?openid=".$openid."'>搜索课程教材</a>\n".
                    "<a href='http://www.itshuquan.com/order_fb?openid=".$openid."'>教材订购反馈</a>";
			}else if($user->certificate_as==""){
				$reply = '请先认证您的身份，<a href="http://www.itshuquan.com/userinfo/basic?openid='.$openid.'">点此进行认证身份</a>';
			}else{
				$reply = "您当前认证的身份为：$user->certificate_as，只有教师用户才可申请样书";
			}
            Log::info("处理模块: BookReq");
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
		if(($this->message->MsgType == 'text' && strstr($this->message->Content,'样书'))||($this->message->MsgType == 'event' && $this->message->Event == 'CLICK' && $this->message->EventKey=='bookreq'))
			return true;
		return false;
	}

	public function name()
	{
		return '样书申请';
	}

	public function weight()
	{
		return 100;
	}
}
