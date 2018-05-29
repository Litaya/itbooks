<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 2018/5/29
 * Time: 下午11:51
 */

namespace App\Dao;

use App\Models\Resource;
use App\Models\User;

class ResourceDao{

	public function getAllResource(User $user, $book_id = null){
		# 先获取每个人都可获取的资源
		$common_resources = Resource::where('owner_book_id', null)->get();

		# 再获取对应书籍的所有资源
		$book_resources = Resource::where('owner_book_id',$book_id)->get();

		# 对于每个资源，检测其用户权限
		$resources = array();
		$resources = array_merge($resources, $common_resources);
		# 对每个资源进行用户权限检测
		foreach ($book_resources as $resource){
			if ($resource->checkUserValidation($user, $book_id)){
				array_push($resources, $resource);
			}
		}
		return $resources;
	}
}