<?php
namespace App\Libraries\WechatModules;

use App\Libraries\WechatHandler;
use App\Libraries\WechatMessageSender;
use App\Models\Book;
use App\Models\Material;
use EasyWeChat\Message\News;
use Illuminate\Support\Facades\Log;
use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;

class Search extends WechatHandler {

	public function handle()
	{
		$msg_type = $this->message->MsgType;
		$openid  = $this->message->FromUserName;
		if($msg_type == 'text'){

			$search_msg  = $this->message->Content;

			$search_msg = trim($search_msg);
			$book_results = Book::search($search_msg)->orderBy('weight','desc')->orderBy('publish_time','desc')->get();

			$book_news = [];
			if(sizeof($book_results)!=0){
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
			}
			$material_results = Material::search($search_msg)->orderBy('wechat_update_time','desc')->get();
            $material_news    = [];
            foreach ($material_results as $material_result){
            	if(sizeof($material_news) < 5){
            		array_push($material_news,new News([
            			'title'       => substr($material_result->title,0,100),
			            'description' => '',
			            'url'         => route('material.show',['id'=>$material_result->id]),
			            'image'       => url($material_result->cover_path)
		            ]));
	            }else{
            		break;
	            }
	            $material_new = new News([
		            'title'       => '共搜到'.sizeof($material_results).'条相关文章，点此查看',
		            'description' => '',
		            'url'         => route('material.index')."?search=$search_msg",
		            'image'       => route('image',['src'=>'public/material.png'])
	            ]);
            	array_push($material_news,$material_new);
            }
			WechatMessageSender::sendNews($openid,$book_news);
			return $material_news;
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
