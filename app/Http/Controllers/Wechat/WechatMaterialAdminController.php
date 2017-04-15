<?php

namespace App\Http\Controllers\Wechat;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Wechat;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WechatMaterialAdminController extends Controller
{
	// 图文管理首页
	public function index(Request $request){
		if($request->has('search')){
			$search    = $request->get('search');
			$materials = Material::search($search)->paginate(10);
		}else{
			$search    = "";
			$materials = Material::orderBy('wechat_update_time','desc')->paginate(10);
		}
		return view('admin.material.index',compact('materials','search'));
	}

	public function set_display(Request $request,$id){
		$this->validate($request,[
			"display" => "required"
		]);
		$display  = $request->get('display');
		Material::where('id',$id)->update(['display'=>$display]);
		$request->session()->flash('notice_message','操作成功');
		$request->session()->flash('notice_status','success');
		return 'success';
	}

	public function drop(Request $request,$id){
		Material::destroy($id);
		$request->session()->flash('notice_message','操作成功');
		$request->session()->flash('notice_status','success');
		return 'success';
	}

	// 图文详情页
	public function show(Request $request, $id){
		$material = Material::where('id',$id)->first();
		return view('admin.material.show',compact('material'));
	}

	// 同步微信图文列表
	public function sync(Request $request){
		$wechatModel = Wechat::getInstance();
		if($request->has('start_time') && $request->has('end_time')){
			$news_sum = $wechatModel->storeWechatNewsToDBbyTime($request->get('start_time'),$request->get('end_time'));
		}else{
			$news_sum = $wechatModel->storeWechatNewsToDB();
		}
		$request->session()->flash('notice_message',"已更新 $news_sum 篇图文");
		$request->session()->flash('notice_status','success');
		return 'success';
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

		// 先存所有的图
		$wechatModel = Wechat::getInstance();
		$wechatModel->storeWechatImagesToDB();

		// 获取EasyWechat的$app实例
		$app      = $wechatModel->getApp();
		$material = $app->material;

		# 数据库里最新的记录，断点标记
		$newest_media_id    = -1;
		$newest_in_db       = Material::orderBy('wechat_update_time','desc')->first();
		Log::info($newest_in_db);
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
				# $news = $list->content->news_item;
				$news = $material->get($list->media_id);
				$news = (object)$news["news_item"];
				foreach ($news as $new){
					$new = (object)$new;
					Material::create([
						'media_id'           => $list->media_id,
						'title'              => $new->title,
						'cover_path'         => '',#$this->storeCover($temporary,$list->media_id),
						'show_cover_pic'     => $new->show_cover_pic,
						'author'             => $new->author,
						'digest'             => $new->digest,
						'url'                => $new->url,
						'content_source_url' => $new->content_source_url,
						'reading_quantity'   => 0,
						'category_id'        => 0,
						'wechat_update_time'  => date('Y-m-d H:i:s',$list->update_time) # 该素材在微信后台的最后更新时间
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

	// 存储封面图
	private function storeCover($material,$thumb_media_id){
		$folder = FileHelper::materialFolder();
		$image = $material->getStream($thumb_media_id);
		$file_name = $thumb_media_id.".jpg";
		file_put_contents(storage_path($folder.$file_name),$image);
		return '/image/'.$folder.$file_name;
	}
}
