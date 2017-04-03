<?php 

namespace App\Libraries;

use App\Models\User;
use App\Models\UserInfo;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\Log;
use EasyWeChat\Message\News;

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
			case 'unsubscribe':
				$reply = $this->unsubscribe();
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
        $reply = '';
        Log::info($this->message);
		switch ($this->message->MsgType){
			case 'text':
                $reply = 'hello';
				break;
			default:
                $reply = '';
				break;
		}
		return $reply;
	}

	/*
	 * 以下函数仅供本类使用
	 */
	private function subscribe(){
		$open_id = $this->message->FromUserName;
		$wechat_user = $this->app->user->get($open_id);
		$user = User::where('openid',$open_id)->first();
		if(empty($user)){
			$user = User::create([
				'username'   => $wechat_user->nickname,
				'openid'     => $open_id,
				'gender'     => $wechat_user->sex,
				'subscribed' => 1,
				'headimgurl' => $wechat_user->headimgurl,
				'source'     => 'wechat'
			]);
			UserInfo::create([
				'user_id' => $user->id
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
		$bookreq_url     = url('/bookreq')."?openid=$openid";
		$certificate_url = url('/cert/create')."?openid=$openid";
		switch ($key){
			case 'bookreq':
				$news = new News([
						'title'       => '申请样书',
						'description' => "点此申请样书",
						'url'         => $bookreq_url,
						'image'       => route('image',['src'=>'public/bookreq.png']),
				]);
				$reply = $news;	
				break;
			case 'certificate':
				$news = new News([
                        'title'       => '身份认证',
                        'description' => "点此认证您的身份",
                        'url'         => $certificate_url,
                        'image'       => route('image',['src'=>'public/cert.png']),
                ]);
				$reply = $news;
				break;
			default:
				$reply = "";
				break;
		}
		return $reply;
	}
}
