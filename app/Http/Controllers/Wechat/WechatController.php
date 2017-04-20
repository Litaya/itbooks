<?php

namespace App\Http\Controllers\Wechat;

use App\Libraries\WechatHandler;
use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\Log;

class WechatController extends Controller
{
	protected $app;
	public function index(){
		return $this->check();
	}
	public function test(){
		return 'hi';
	}

	private function check(){
		$options = [
			'debug'  => true,
			'app_id' => env('APP_ID'),
			'secret' => env('APP_SECRET'),
			'token'  => 'shuquan',

			'log'    => [
				'level' => 'debug',
			'file'  => '/tmp/easywechat.log'
			]
		];
		$app = new Application($options);
		$response = $app->server->serve();

		return $response;
	}

	public function server(){
		$options = [
			'debug'  => true,
			'app_id' => env('APP_ID'),
			'secret' => env('APP_SECRET'),
			'token'  => env('APP_TOKEN'),

			'log'    => [
				'level' => 'debug',
			'file'  => '/tmp/easywechat.log'
			]
		];
		$app    = new Application($options);
		$server = $app->server;
		$this->app = $app;
		$server->setMessageHandler(function($message){
                Log::info("[微信消息]");
                Log::info($message);
				$handler = WechatHandler::getMessageHandler($this->app,$message);
				$reply = $handler->handle();
                return $reply;
				});
		$response = $server->serve();
		return $response;
	}
}
