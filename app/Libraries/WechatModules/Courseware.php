<?php

namespace App\Libraries\WechatModules;

use App\Libraries\WechatHandler;
use App\Models\Book;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Courseware extends WechatHandler{
	public function handle()
	{

		$msg_type = $this->message->MsgType;
		$match    = false;
		$reply    = "";
		if($msg_type == 'text'){
			$content = $this->message->Content;
			$content = trim($content);
			if(preg_match("/[^#]+#[0-9]+/",$content)) {

				$openid = $this->message->FromUserName;
				$user   = User::where('openid',$openid)->first();
				$user_info = UserInfo::where('user_id',$user->id)->first();
				$book_url = url('/home')."?openid=$openid";
				if(empty($user_info) || empty($user_info->role)){
					$match = true;
					$reply = "只有注册用户才可下载课件，<a href='http://www.itshuquan.com/userinfo/basic?openid=".$openid."'>点此注册</a>";
				}else{
					$content_arr = explode('#',$content);
					if($content_arr[0] == '课件'){
						$isbn   = $content_arr[1];
						$book   = Book::where('isbn','like',"%$isbn")->first();

						if(empty($book)){
							$match = true;
							$reply = "该书不存在，如果有问题请在后台联系管理员";
						}else{
							$code   = $book->department->code;
							$kj_url = \App\Models\Courseware::getCourseware($book->id);
							if(empty($kj_url)){
								$match = true;
								$reply = "本书没有课件";
							}else{
								$pass   = \App\Models\Courseware::getCoursewarePassword($isbn,$code);
								$match = true;
								$reply = "课件下载地址：$kj_url \n 课件密码：$pass";
							}
						}
					} else if($content_arr[0] == '密码'){
						$isbn   = $content_arr[1];
						$book   = Book::where('isbn','like',"%$isbn")->first();
						$code   = $book->department->code;
						$pass   = \App\Models\Courseware::getCoursewarePassword($isbn,$code);
						$reply = "课件密码：$pass";
					}
					$reply = $reply."\n<a href='".$book_url."'>更多图书资源</a>";
				}
			}
		}

		if($match){
			Log::info("处理模块：Courseware");
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
	public function name(){
		return '课件密码';
	}
	public function weight()
	{
		return 10;
	}
}
