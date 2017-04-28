<?php

namespace App\Models;

use App\Helpers\FileHelper;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\Log;

/**
 * Class Wechat,采用单例模式,负责关于微信的所有数据
 * @package App\Models
 */
class Wechat
{
	private static $instance = NULL;
	protected $app = NULL;

	/**
	 * Wechat constructor.
	 */
	private function __construct()
	{
		$options = [
			'debug'  => true,
			'app_id' => env('APP_ID'),
			'secret' => env('APP_SECRET'),
			'token'  => 'shuquan',

			'log'    => [
				'level' => 'debug',
				'file'  => '/tmp/easywechat.log'
			]
		];
		$this->app = new Application($options);
	}

	/**
	 * 返回可用的Wechat对象
	 * @return Wechat
	 */
	public static function getInstance(){
		if (is_null(self::$instance)){
			self::$instance = new Wechat();
		}
		return self::$instance;
	}

	/**
	 * @return Application|null
	 */
	public function getApp(){
		return $this->app;
	}

	/**
	 * 获取永久素材列表
	 * @param $type string 素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）
	 * @param $offset int 从全部素材的该偏移位置开始返回，0表示从第一个素材返回
	 * @param $count int 返回素材的数量，取值在1到20之间
	 * @return array
	 */
	public function getMaterialLists($type,$offset,$count){
		$material = $this->app->material;
		$lists    = $material->lists($type,$offset,$count);
		return $lists;
	}

	/**
	 * 通过 $thumb_media_id 获取图片链接
	 * @param $thumb_media_id int
	 */
	public function getImageByThumbMediaId($thumb_media_id){

	}

	/**
	 * 将素材库的图片存储于本地，并将本地路径存库。
	 * @return int 本次更新的图片数量
	 */
	public function storeWechatImagesToDB(){
		$offset = 0; $count = 20; $image_sum = 0;
		while (1){
			$images = $this->getMaterialLists('image',$offset,$count);
			$items  = $images['item'];

			$updated = false; # 本次获取没有任何更新
			# 遍历获取到的每张图片，如果有未存库的，存库
			foreach ($items as $item){
				$db_img = WechatImgUrl::where('thumb_media_id',$item["media_id"])->get();
				if(sizeof($db_img)==0){ //如果还未存库
					WechatImgUrl::create([
						'thumb_media_id' => $item["media_id"],
						'url'            => $item["url"],
						'local_url'      => FileHelper::storeImageFromUrl($item["url"],$item["media_id"])
					]);
					$image_sum += 1;
					$updated = true;
				}
			}

			# 如果本次循环没有任何更新，则代表已经更新完最新图片，退出更新循环
			if(!$updated)
				break;

			$offset += $count;
		}
		return $image_sum;
	}

	/**
	 * 将素材库的所有图文消息内容存本地，并将路径存库
	 * @param array|null $time， time['start'] time['end'] timestamp类型
	 * @return int
	 */
	public function storeWechatNewsToDB(){
		$this->storeWechatImagesToDB(); // 先将所有的图片素材存库
		$offset = 0; $count = 20; $news_sum = 0;
		while (1){
			$lists = $this->getMaterialLists('news',$offset,$count);
			$news  = $lists['item'];

			$updated = false;
			foreach ($news as $new){
				$media_id    = $new['media_id'];
				$update_time = $new['update_time'];
				$new_in_db   = Material::where('media_id',$media_id)->get();

				if(sizeof($new_in_db)==0 || $offset <= 20){
					Material::where('media_id',$media_id)->delete();
					# 图文入库
					$items = $new['content']['news_item'];
					# 对于多图文消息
					foreach ($items as $item){
						$thumb_media_id = $item['thumb_media_id'];
						$img_in_db      = WechatImgUrl::where('thumb_media_id',$thumb_media_id)->first();
						$cover_path     = empty($img_in_db)?'/img/example.jpg':$img_in_db->local_url;
						Material::create([
							'media_id'           => $media_id,
							'title'              => $item['title'],
							'thumb_media_id'     => $thumb_media_id,
							'cover_path'         => $cover_path,
							'show_cover_pic'     => $item['show_cover_pic'],
							'author'             => $item['author'],
							'digest'             => $item['digest'],
							'content'            => $item['content'],
							'url'                => $item['url'],
							'content_source_url' => $item['content_source_url'],
							'reading_quantity'   => 0,
							'category_id'        => 0,
							'wechat_update_time'  => date('Y-m-d H:i:s',$update_time) # 该素材在微信后台的最后更新时间
						]);
						$news_sum += 1;
					}
					$updated = true;
				}
			}
			if(!$updated)
				break;
			$offset += $count;
		}
		return $news_sum;
	}

	public function storeWechatNewsToDBbyTime($start_time, $end_time){
		$start_time = strtotime($start_time);
		$end_time   = strtotime($end_time);

#        Log::info("start: $start_time...... end: $end_time");
		$this->storeWechatImagesToDB(); // 先将所有的图片素材存库
		$offset = 0; $count = 20; $news_sum = 0;
		while (1){
			$lists = $this->getMaterialLists('news',$offset,$count);
			$news  = $lists['item'];

			# 对于获取到的素材列表中的每一条素材
			$smallest = time()+7200;
			$largest  = 0;
			foreach ($news as $new){
				$media_id    = $new['media_id'];
				$update_time = intval($new['update_time']);

				#Log::info("start: $start_time...... end: $end_time........update_time:$update_time");

				# 更新本次获取的列表中的最大、最小时间.
				if($smallest > $update_time) $smallest = $update_time;
				if($largest  < $update_time) $largest  = $update_time;

				# 如果本条时间不在目标时间内，跳过
				if($update_time > $end_time || $update_time < $start_time) {
					continue;
				}

				# 如果在目标时间内，则更新
				$old_materials = Material::where('media_id',$media_id)->get();
				$delete_mark   = [];
				foreach ($old_materials as $old_material){
					$delete_mark[$old_material->title] = 1;
				}
				# 图文入库
				$items = $new['content']['news_item'];
				# 对于多图文消息
				foreach ($items as $item){
					$thumb_media_id = $item['thumb_media_id'];
					$img_in_db      = WechatImgUrl::where('thumb_media_id',$thumb_media_id)->first();
					$cover_path     = empty($img_in_db)?'/img/example.jpg':$img_in_db->local_url;

					// 如果获取到的新的素材与库中的某素材同名，则更新同名素材，不同名的，则新建。
					if(isset($delete_mark[$item['title']])){
						Material::where('title',$item['title'])->update([
							'thumb_media_id'     => $thumb_media_id,
							'cover_path'         => $cover_path,
							'show_cover_pic'     => $item['show_cover_pic'],
							'author'             => $item['author'],
							'digest'             => $item['digest'],
							'content'            => $item['content'],
							'url'                => $item['url'],
							'content_source_url' => $item['content_source_url'],
							'wechat_update_time'  => date('Y-m-d H:i:s',$update_time) # 该素材在微信后台的最后更新时间
						]);
						unset($delete_mark[$item['title']]);
					} else{
						Material::create([
							'media_id'           => $media_id,
							'title'              => $item['title'],
							'thumb_media_id'     => $thumb_media_id,
							'cover_path'         => $cover_path,
							'show_cover_pic'     => $item['show_cover_pic'],
							'author'             => $item['author'],
							'digest'             => $item['digest'],
							'content'            => $item['content'],
							'url'                => $item['url'],
							'content_source_url' => $item['content_source_url'],
							'reading_quantity'   => 0,
							'category_id'        => 0,
							'wechat_update_time'  => date('Y-m-d H:i:s',$update_time) # 该素材在微信后台的最后更新时间
						]);
					}
					$news_sum += 1;
				}
			}

			# 如果本次更新的最晚时间早于时间段的开始端，或者本次更新的最早时间大于时间段的结束端，则更新
			if($largest < $start_time || $smallest > $end_time)
				break;
			$offset += $count;
		}
		return $news_sum;
	}

	// 更新全部的微信素材
	public function updateAllWechatMaterials(){
		$offset = 0;
		$count  = 20;
		$sum    = 0;
		while (1){
			$lists = $this->getMaterialLists('news',$offset,$count);

			$has_update = false;

			$news  = $lists['item'];
			foreach ($news as $new){
				$media_id    = $new['media_id'];
				$update_time = $new['update_time'];
				$items       = $new['content']['news_item'];

				Material::where('media_id',$media_id)->delete();
				foreach ($items as $item){
					$thumb_media_id = $item['thumb_media_id'];
					$img_in_db      = WechatImgUrl::where('thumb_media_id',$thumb_media_id)->first();
					$cover_path     = empty($img_in_db)?'/img/example.jpg':$img_in_db->local_url;
					$has_update = true;
					Material::create([
						'media_id'           => $media_id,
						'title'              => $item['title'],
						'thumb_media_id'     => $thumb_media_id,
						'cover_path'         => $cover_path,
						'show_cover_pic'     => $item['show_cover_pic'],
						'author'             => $item['author'],
						'digest'             => $item['digest'],
						'content'            => $item['content'],
						'url'                => $item['url'],
						'content_source_url' => $item['content_source_url'],
						'reading_quantity'   => 0,
						'category_id'        => 0,
						'wechat_update_time'  => date('Y-m-d H:i:s',$update_time) # 该素材在微信后台的最后更新时间
					]);
					$sum += 1;
					if($sum%100 == 0){
						Log::info($sum);
					}
				}
			}
			if(!$has_update) break;
			$offset += $count;
		}
	}

	public function storeWechatUserToDB(){

		$userService = $this->app->user;
		#$next_openid = "oG09KxI_62wEES5uv_-KV-XnWEzQ";
		#$next_openid = "oG09KxFmqgCS3j838CtmpFVt6Ab0";
		#$next_openid = "oG09KxN8I0Oqxndi6iTnoVy-gCko";
		$next_openid = "oG09KxDiYemhUEwuYCksw6nM9kDw";
		$data        = $userService->lists($next_openid);
		$openids     = $data["data"]["openid"];
		$next_openid = $data["next_openid"];

		$count = 0;

		foreach ($openids as $openid){
			$count += 1;
			$user = User::where('openid',$openid)->get();
			if(sizeof($user) == 0){
				$user = $userService->get($openid);
				User::create([
					'openid'     => $openid,
					'username'   => $user["nickname"],
					'gender'     => $user["sex"],
					'headimgurl' => $user["headimgurl"],
					'subscribed' => 1,
					'source'     => 'wechat'
				]);
			}else{
				User::where('openid',$openid)->update(['subscribed'=>1]);
			}
			//Log::info($openid);
			if($count % 100 == 0){
				Log::info($count);
			}
		}
		Log::info("next_openid:".$next_openid);
	}
}
