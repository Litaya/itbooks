<?php 

namespace App\Libraries;

use App\Models\User;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\Log;

class WechatMessageHandler{

	protected $message;
	protected $app;

	public function __construct($app,$message){
		$this->message = $message;
		$this->app = $app;
	}

	public function handle(){
		if(!empty($this->message)){
			if($this->message->MsgType=='event'){
				$reply =  $this->eventHandler();
			}else{
				$reply = $this->messageHandler();
			}
		}else
			$reply = "消息格式错误";
		return $reply;
	}

	private function eventHandler(){
		$reply = '';
		switch ($this->message->Event){
			case 'subscribe':
				$reply = $this->subscribe();
				break;
			case 'CLICK':
				$reply = $this->click();
				break;
			default:
				$reply = "";
				break;
		}
		return $reply;
	}

	private function messageHandler(){
		switch ($this->message->Event){
			case 'Text':
				break;
			default:
				break;
		}
		return '';
	}

	/*
	 * 以下函数仅供本类使用
	 */
	private function subscribe(){
		$open_id = $this->message->FromUserName;
		$wechat_user = $this->app->user->get($open_id);
		$user = User::where('openid',$open_id)->first();
		if(empty($user)){
			User::create([
				'username'=>$wechat_user->nickname,
				'openid'=>$open_id,
				'gender'=>$wechat_user->sex,
				'subscribed'=>1,
				'headimgurl'=>$wechat_user->headimgurl,
				'source'=>'wechat'
			]);
		}else{
			User::where('openid',$open_id)->update(['subscribed'=>1]);
		}
		return "欢迎关注书圈";
	}

	private function unsubscribe(){
		$openid = $this->message->FromUserName;
		User::where('openid',$openid)->update(['subscribed'=>0]);
		return '';
	}

	private function click(){
		$openid = $this->message->FromUserName;
		$key = $this->message->EventKey;
		$reply = '';
        $apply_url = 'http://www.baidu.com';
		//$apply_url = url('/user/teacher/apply')."?openid=$openid";
		$bookreq_url     = url('/book')."?openid=$openid";
		$certificate_url = url('/cert/create')."?openid=$openid";
		switch ($key){
			case 'bookreq':
				$reply = "<a href='$bookreq_url'>点此查看可申请书籍</a>";
				break;
			case 'certificate':
				$reply = "<a href='$certificate_url'>点此进行身份认证</a>";
				break;
			default:
                $reply = "";
				break;
		}
		return $reply;
	}
}
