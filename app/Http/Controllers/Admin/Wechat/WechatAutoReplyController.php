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
		$reply  = "";
		foreach ($p_tags as $p_tag){
			$p_tag_content = trim($p_tag->innertext);

			# 修改<br>为\n
			$inner_content = str_replace("<br>","\n",$p_tag_content);

            # 消除 target = "_blank"
			$inner_content = str_replace('target="_blank"','',$inner_content);

            # 添加换行符
			if(substr($inner_content,strlen($inner_content)-1,1)!=="\n"){
				$reply = $reply.$inner_content."\n";
			}else{
				$reply = $reply.$inner_content;
			}
		}
		return trim($reply);
	}

	public function destroy(Request $request,$id){
		WechatAutoReply::destroy($id);
		$request->session()->flash('wechat_message','操作成功！');
		$request->session()->flash('wechat_status','success');
		return 'success';
	}

	public function storeEdit(Request $request){
		$this->validate($request,[
			'auto_reply_id',
			'alter_regex',
			'alter_reply'
		]);
		$regex = $request->get('alter_regex');
//		$reply = $request->get('alter_reply');
		$replyhtml = new Htmldom($request->get('alter_reply'));
		$reply = $this->resolveEditorText((string)$replyhtml);
		$reply_id = $request->get('auto_reply_id');

		WechatAutoReply::where('id',$reply_id)->update(['regex'=>$regex,'content'=>$reply,'type'=>0]);
		$request->session()->flash('notice_message','成功！');
		$request->session()->flash('notice_status','success');

		return redirect()->route('admin.wechat.auto_reply.index');
	}

}
