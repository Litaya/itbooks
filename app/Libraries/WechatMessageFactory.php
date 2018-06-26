<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 2018/6/26
 * Time: 上午11:05
 */

namespace App\Libraries;

use App\Dao\ResourceDao;
use App\Libraries\WechatModules\Book;
use App\Models\User;
use App\Models\UserInfo;

class WechatMessageFactory{
	public static $COURSEWARE_HINT  = 0;
	public static $COURSEWARE_REPLY = 1;
	public static $CW_BR_PWD_HINT   = 2;
	public static $CW_BR_PWD_REPLY  = 3;


	/**
	 * @param $message object
	 * @param $type integer
	 * @param $memo array
	 * @return string reply message.
	 */
	public function factory($message, $type, $memo = null){
		switch ($type){
			case self::$COURSEWARE_HINT:
				return $this->courseware_hint();
			case self::$COURSEWARE_REPLY:
				return $this->courseware_reply($message);
			case self::$CW_BR_PWD_HINT:
				return $this->cw_br_pwd_hint($message);
			default:
				return '';
		}
	}

	private function courseware_hint(){
		$reply = "请在公众号后台按下面格式回复:\n".
			"【1】课件#书号，例如：课件#9787302307488\n".
			"【2】密码#书号，例如9787302307488\n"
			."【3】书号，例如，9787302307488\n\n".
			"注：\n".
			"（1）书号是封底的ISBN号（13位数字，不用加横线）\n".
			"（2）不要在#前后加空格\n".
			"（3）点击微信公众号界面下方的小键盘图标，可以在文本框中输入回复内容";
		return $reply;
	}

	private function courseware_reply($message){
		$openid    = $message->FromUserName;
		$user      = User::where('openid',$openid)->first();
		$content   = $message->Content;
		$book_url = url('/home')."?openid=$openid";
		if(empty($user_info) || empty($user_info->role)){
			return "只有注册用户才可下载课件，<a href='http://www.itshuquan.com/userinfo/basic?openid=".$openid."'>点此注册</a>";
		}
		$content_arr = explode('#',$content);
		$isbn   = $content_arr[1];
		$book      = Book::where('isbn','like',"%$isbn")->first();
		if (empty($book)){
			return "该书不存在，如果有问题请在后台联系管理员";
		}
		$code   = $book->department->code;
		$kj_url = \App\Models\Courseware::getCourseware($book->id);
		$pass   = \App\Models\Courseware::getCoursewarePassword($isbn,$code);
		$reply  = '';
		if ($content_arr[0] == '课件'){
			if(empty($kj_url)){
				return "本书没有课件";
			}
			$resources_str = "";
			$resourceDao = new ResourceDao();
			$resources = $resourceDao->getAllResource($user, $book->id);
			foreach ($resources as $resource){
				$resources_str = $resources_str."\n\n【".$resource->title."】\n资源简介：".$resource->description."\n下载地址：".$resource->file_upload;
			}
			$reply = "课件下载地址：$kj_url \n课件密码：$pass";
			if(sizeof($resources) > 0){
				$reply = $reply."\n\n其他资源".$resources_str;
			}
		}else if ($content_arr[0] == '密码'){
			$reply = "课件密码：$pass";
		}
		$reply = $reply."\n\n<a href='".$book_url."'>查看更多图书资源</a>";
		return $reply;
	}

	private function cw_br_pwd_hint($message){
		$openid    = $message->FromUserName;
		$user = User::where('openid',$openid)->first();
		$book_url = url('/home')."?openid=$openid";
		$cw_url   = route('prompt.courseware');
		$reply = "<a href='".$book_url."'>搜索图书资源</a>";
		$reply .= "\n下载课件密码";

		if(!empty($user)&&strpos($user->certificate_as,'TEACHER')!==false){
			$reply.="\n<a href='http://www.itshuquan.com/bookreq?openid=".$openid."'>申请教材样书</a>";
			$reply.= "\n<a href='http://www.itshuquan.com/bookreq/record?openid=".$openid."'>查看样书记录</a>\n".
				"<a href='http://www.itshuquan.com/order_fb?openid=".$openid."'>教材订购反馈</a>";
		}else if($user->certificate_as=="") {
			$reply.="\n<a href='http://www.itshuquan.com/bookreq?openid=".$openid."'>申请教材样书</a>";
		}

		return $reply;
	}
}