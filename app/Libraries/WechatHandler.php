<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 14/04/2017
 * Time: 10:55 AM
 */

namespace App\Libraries;

use App\Models\WechatModuleModel;

abstract class WechatHandler{

	protected $successor;
	protected $message;
	protected $app;

	public function __construct($app=NULL,$message=NULL)
	{
		$this->app     = $app;
		$this->message = $message;
	}

	public function setSuccessor(WechatHandler $successor){
		$this->successor = $successor;
	}

	/**
	 * 处理文本消息的核心逻辑
	 * @param $message
	 * @return mixed
	 */
	abstract public function handle();

	/**
	 * 每个模块有自己的默认权重，自动加载微信处理模块的时候按照权重形成责任链。
	 * @return int
	 */
	abstract public function weight();

	/**
	 * 设置每个模块的默认名称
	 * @return mixed
	 */
	abstract public function name();

	/**
	 * @param $message
	 * 按照权重的高低组织消息处理链
	 */
	public static function getMessageHandler($app,$message){

		$modules       = WechatModuleModel::orderBy('weight','desc')->get();
		$module_prefix = '\App\Libraries\WechatModules\\';
		$handler       = null;
		$pre_handler   = null;
		$first         = true;

		foreach ($modules as $module){
			$module_class = $module_prefix.$module->module;
			$module_handler = new $module_class($app,$message);
			if($first){
				$handler     = $module_handler;
				$pre_handler = $handler;
				$first   = false;
			}else{
				if($module->status == 1){
					$pre_handler->setSuccessor($module_handler);
					$pre_handler = $module_handler;
				}
			}
		}

		return $handler;
	}
}
