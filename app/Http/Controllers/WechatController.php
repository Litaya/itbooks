<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\WechatMessageHandler;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\Log;

class WechatController extends Controller
{
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
				Log::info("【 wechat message received 】\n".$message);
				$handler = new WechatMessageHandler($this->app,$message);
				return $handler->handle();
				});
		$response = $server->serve();
		return $response;
	}
}
