<?php
namespace App\Libraries\WechatModules;

use App\Libraries\WechatTextHandler;
use App\Models\User;

class BookReq extends WechatTextHandler{
	public function handle($openid, $message)
	{
		// 如果有样书
		if (strstr($message,'样书')){
			$user = User::where('openid',$openid)->first();
			if(strpos($user->certificate_as,'TEACHER')!==false){
				$reply = '只有认证的教师才可以申请教材样书（样书会在5个工作日内处理）。\n'.
					'<a href="http://www.itshuquan.com/bookreq?openid='.$openid.'">申请教材样书</a>'.
					'<a href="http://www.itshuquan.com/bookreq/record?openid='.$openid.'">查看样书记录</a>\n '.
					'<a href="http://www.itshuquan.com/home?openid=openidvalue">搜索课程教材</a>';
			}else if($user->certificate_as==""){
				$reply = '只有认证的教师才可以申请教材样书（样书会在5个工作日内处理）\n <a href="http://www.itshuquan.com/userinfo/basic?openid='.$openid.'">点此认证教师身份</a>';
			}else{
				$reply = "只有教师用户才可申请样书";
			}
			return $reply;
		}

		# 责任链没有断的情况下，继续向下处理
		if(!empty($this->successor)){
			return $this->successor->handle($openid,$message);
		}else{ # 没有下一个处理模块，则返回空串
			return "";
		}

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