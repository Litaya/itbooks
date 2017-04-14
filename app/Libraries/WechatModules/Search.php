<?php
namespace App\Libraries\WechatModules;

use App\Libraries\WechatHandler;
use App\Models\Book;
use App\Models\Material;
use EasyWeChat\Message\News;
use Illuminate\Support\Facades\Log;

class Search extends WechatHandler {

	public function handle()
	{
		$msg_type = $this->message->MsgType;
		$openid  = $this->message->FromUserName;
		if($msg_type == 'text'){
			$search_msg  = $this->message->Content;
			$book_results = Book::search($search_msg)->orderBy('weight','desc')->orderBy('publish_time','desc')->get();
			if(sizeof($book_results)!=0){
				$book_news = [];
				foreach ($book_results as $book_result){
					if(sizeof($book_news) < 5){
						array_push($book_news,new News([
							'title'       => substr($book_result->name."|".$book_result->authors,0,100),
							'description' => '',
							'url'         => route('book.show',$book_result->id),
							'image'       => empty($book_result->img_upload)?route('image',['src'=>'public/book.png']):$book_result->img_upload
						]));
					}else
						break;
				}
				$book_new = new News([
					'title'       => "共查询到".sizeof($book_results)."本相关图书",
					'description' => "点此查看相关图书列表",
					'url'         => route('book.index')."?search=".$search_msg."&openid=$openid",
					'image'       => route('image',['src'=>'public/book.png'])
				]);
				array_push($book_news,$book_new);
                Log::info('处理模块: Search');
				return $book_news;
			}
		}
		if(!empty($this->successor)){
            Log::info('模块['.$this->name().']无法处理，传递给下一个模块');
			return $this->successor->handle();
		}else{
            Log::info('模块['.$this->name().']是最后一个模块');
			return "";
		}
	}

	public function name()
	{
		return '号内搜';
	}

	public function weight()
	{
		return 1;
	}
}
