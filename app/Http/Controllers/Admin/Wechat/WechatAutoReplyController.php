<?php

namespace App\Http\Controllers\Admin\Wechat;

use App\Http\Controllers\Controller;
use App\Models\WechatAutoReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yangqi\Htmldom\Htmldom;

class WechatAutoReplyController extends Controller
{
	public function index(){
		$wechat_auto_replies = WechatAutoReply::all();
		return view('admin.wechat.auto_reply',compact('wechat_auto_replies'));
	}

	public function store(Request $request){
		$this->validate($request,[
			'regex' => 'required',
			'reply' => 'required'
		]);
		$type = 0; // TODO 目前只支持文字消息，后期支持图片、图文
		$regex = $request->get('regex');
		$replyhtml = new Htmldom($request->get('reply'));
		$reply = $this->resolveEditorText((string)$replyhtml);
		$auto_reply = WechatAutoReply::where('regex',$regex)->get();

		if(sizeof($auto_reply)>0){
			WechatAutoReply::where('regex',$regex)->update(['content'=>$reply,'type'=>$type]);
		}else{
			WechatAutoReply::create([
				'regex'   => $regex,
				'content' => $reply,
				'type'    => $type
			]);
		}
		return redirect()->route('admin.wechat.auto_reply.index');
	}

	private function resolveEditorText($content){
		$html   = new Htmldom($content);
		$p_tags = $html->find('p');
		$first  = true;
		$reply  = "";
		foreach ($p_tags as $p_tag){
			$p_tag_content = $p_tag->innertext;

			# 消除<br>
			$inner_content = "";
			$p_tag_content_arr = explode('<br>',$p_tag_content);
			foreach ($p_tag_content_arr as $text){
				$inner_content = $inner_content.$text;
			}

            # 消除 target = "_blank"
			$inner_content_2 = "";
			$a_tag_content_arr = explode('target="_blank"',$inner_content);
			foreach ($a_tag_content_arr as $text){
				$inner_content_2 = $inner_content_2.$text;
			}

			$inner_content = $inner_content_2;
			$inner_content = trim($inner_content);
			if(empty($inner_content)) continue;

            # 添加换行符
			if(!$first){
				$reply = $reply."\n".$inner_content;
			}else{
				$reply = $inner_content;
			}
			if(!empty($inner_content)){
				$first = false;
			}
		}
		return $reply;
	}

	public function destroy(Request $request,$id){
		WechatAutoReply::destroy($id);
		$request->session()->flash('wechat_message','操作成功！');
		$request->session()->flash('wechat_status','success');
		return 'success';
	}
}
