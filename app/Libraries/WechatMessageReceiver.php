<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 14/04/2017
 * Time: 10:57 AM
 */

namespace App\Libraries;

use App\Models\User;
use App\Models\UserInfo;
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
		return WechatHandler::getMessageHandler($this->app,$this->message)->handle();
	}
}
