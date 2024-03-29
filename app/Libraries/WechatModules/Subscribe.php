<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 14/04/2017
 * Time: 10:59 AM
 */

namespace App\Libraries\WechatModules;

use App\Models\User;
use App\Models\UserInfo;
use App\Libraries\WechatHandler;
use Illuminate\Support\Facades\Log;

class Subscribe extends WechatHandler {

	public function handle()
	{
		$msg_type = $this->message->MsgType;
		if($msg_type == 'event'){
			if($this->message->Event=='subscribe') {
				$open_id = $this->message->FromUserName;
				$wechat_user = $this->app->user->get($open_id);
				$user = User::where('openid', $open_id)->first();
				if (empty($user)) {
					$user = User::create([
						'username' => $wechat_user->nickname,
						'openid' => $open_id,
						'gender' => $wechat_user->sex,
						'subscribed' => 1,
						'headimgurl' => $wechat_user->headimgurl,
						'source' => 'wechat'
					]);
					UserInfo::create([
						'user_id' => $user->id
					]);
				} else {
					User::where('openid', $open_id)->update(['subscribed' => 1]);
				}
				$reply = "感谢你的关注！我们会把最精彩的内容第一时间发给你！如果你是第一次光临，请先注册我们的会员，你将能够获得我们更多的服务\n" .
					"<a href='http://www.itshuquan.com/userinfo/basic?openid=" . $open_id . "'>用户注册</a>\n" .
					"<a href='http://www.itshuquan.com/material/cate/4'>新手指南</a>";
                Log::info("处理模块: Subscribe");
				return $reply;
			}else if($this->message->Event=='unsubscribe'){
                Log::info("处理模块: Subscribe[unsubscribe]");
				$openid = $this->message->FromUserName;
				User::where('openid',$openid)->update(['subscribed'=>0]);
				return '';
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

	public function name()
	{
		return '关注/取消关注';
	}

	public function weight()
	{
		return 1;
	}
}
