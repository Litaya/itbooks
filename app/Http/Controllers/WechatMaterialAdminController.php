<?php

namespace App\Http\Controllers;

use App\Models\Material;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;

class WechatMaterialAdminController extends Controller
{
	// 图文管理首页
	public function index(Request $request){
		return view('admin.material.index');
	}

	// 图文详情页
	public function show(){
		// TODO 获取图文消息内容

		return view('admin.material.show');
	}

	// 同步微信图文列表
	public function sync(Request $request){
		$news_sum = $this->updateNews();
        $request->session()->flash('notice_message',"已更新 $news_sum 篇图文");
        $request->session()->flash('notice_status','success');
		return redirect()->route('admin.material.index');
	}

	// 评论详情页
	public function comments(){}

	// 通过评论
	public function passComment(){}

	// 删除评论
	public function dropComment(){}

	private function getWechatApp(){
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
		return $app;
	}

	// 更新数据库的material信息
	private function updateNews(){
		$app                = $this->getWechatApp();
		$material           = $app->material;

		# 数据库里最新的记录，断点标记
		$newest_media_id    = -1;
		$newest_in_db       = Material::orderBy('created_at','desc')->first();
		if(!empty($newest_in_db))
			$newest_media_id    = $newest_in_db->media_id;

		# 初始化变量
		$offset     = 0;
		$count      = 10;
		$item_count = -1;    # 每次取出的item数量
		$finish     = false; # 是否到了断点
        $news_sum   = 0;
		while (1){
			$lists = $material->lists('news',$offset,$count);

			# 如果全部取完，则结束更新
			$item_count = $lists->item_count;
			if($item_count == 0) break;

			# 获取本次取到的图文列表
			$lists = json_decode($lists)->item;

			# 对于每个图文结构体（可能是多图文）
			foreach ($lists as $list){
				# 如果到了断点处，标记，并退出循环
				if($list->media_id == $newest_media_id){
					$finish = true;
					break;
				}

				# 对于多图文结构体，每条图文均需单独存库
				$news = $list->content->news_item;
				foreach ($news as $new){
					Material::create([
                        'media_id'           => $list->media_id,
						'title'              => $new->title,
						'thumb_media_id'     => $new->thumb_media_id,
						'show_cover_pic'     => $new->show_cover_pic,
						'author'             => $new->author,
						'digest'             => $new->digest,
						'url'                => $new->url,
						'content_source_url' => $new->content_source_url,
						'reading_quantity'   => 0,
						'category_id'        => 0
					]);
                    $news_sum += 1; # 统计更新的图文条数
				}# end foreach
			}# end foreach
			# 如果已经到了断点，结束更新
			if ($finish) break;
			$offset += $count;
			break; #先测试一个例子
		} # end while
        return $news_sum;
	}
}
