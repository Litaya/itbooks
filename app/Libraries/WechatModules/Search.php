<?php
namespace App\Libraries\WechatModules;

use App\Libraries\WechatTextHandler;
use App\Models\Book;
use App\Models\Material;
use EasyWeChat\Message\News;

class Search extends WechatTextHandler{

	public function handle($openid,$message)
	{
		$book_result = Book::search($message)->get();
		$material_result = Material::search($message)->get();
		if(sizeof($book_result)!=0 || sizeof($material_result)!=0){
			$book_new = new News([
				'title'       => "查询到".sizeof($book_result)."本相关图书",
				'description' => "点此查看相关图书列表",
				'url'         => route('book.index')."?search=".$message."&openid=$openid",
				'image'       => route('image',['src'=>'public/book.png'])
			]);
			$material_new = new News([
				'title'       => "查询到".sizeof($material_result)."篇相关文章",
				'description' => "点此查看相关文章列表",
				'url'         => route('material.index')."?search=".$message."&openid=$openid",
				'image'       => route('image',['src'=>'public/material.png'])
			]);
			# return [$book_new,$material_new];
            return $book_new;
		}
		if(!empty($this->successor)){
			return $this->successor->handle($openid,$message);
		}else{
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
