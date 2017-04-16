<?php

namespace App\Libraries\WechatModules;

use App\Libraries\WechatHandler;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Courseware extends WechatHandler{
	public function handle()
	{
		$msg_type = $this->message->MsgType;
		if($msg_type == 'text'){
			$content = $this->message->Content;
			$content = trim($content);
			if(preg_match("/[^#]+#[0-9]+/",$content)) {

				$openid = $this->message->FromUserName;
				$user   = User::where('openid',$openid)->first();
				$user_info = $user->user_info;
				if(empty($user_info) || empty($user_info->role)){
					Log::info('处理模块: Course');
					return "只有注册用户才可下载课件，<a href='http://www.itshuquan.com/userinfo/basic?openid=".$openid."'>点此注册</a>";
				}

				$content_arr = explode('#',$content);
				if($content_arr[0] == '课件'){
					$isbn   = $content_arr[1];
					$book   = Book::where('isbn','like',"%$isbn")->first();
					if(empty($book)){
						Log::info('处理模块：Courseware');
						return '该书不存在，如果有问题请在后台联系管理员';
					}
					$code   = $book->department->code;
					$kj_url = \App\Models\Courseware::getCourseware($book->id);
					if(empty($kj_url)){
						Log::info('处理模块：Courseware');
						return '本书没有课件';
					}
					$pass   = \App\Models\Courseware::getCoursewarePassword($isbn,$code);
					Log::info('处理模块：Courseware');
					return "课件下载地址：$kj_url \n 课件密码：$pass";
				}
				if($content_arr[0] == '密码'){
					$isbn   = $content_arr[1];
					$book   = Book::where('isbn','like',"%$isbn")->first();
					if(empty($book)) {
						Log::info('处理模块：Courseware');
						return '该书不存在，如果有问题请在后台联系管理员';
					}
					$code   = $book->department->code;
					$kj_url = \App\Models\Courseware::getCourseware($book->id);
					if(empty($kj_url)){
						Log::info('处理模块：Courseware'); 
						return '本书没有课件';
					}
					$pass   = \App\Models\Courseware::getCoursewarePassword($isbn,$code);
					Log::info('处理模块：Courseware');		
					return "课件密码：$pass";
				}
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
	public function name(){
		return '课件密码';
	}
	public function weight()
	{
		return 10;
	}
}
