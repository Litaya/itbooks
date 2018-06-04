<?php
/**
 * Created by PhpStorm.
 * User: zhangxinru
 * Date: 2018/5/29
 * Time: 下午11:51
 */

namespace App\Dao;

use App\Models\Resource;
use App\Models\ResourceBook;
use App\Models\User;

class ResourceDao{

	public function getAllResource(User $user, $book_id = null){

		# 先获取每个人都可获取的资源
		$common_resources = Resource::where('owner_book_id', 0)->get();
        # 再获取对应书籍的所有资源
        $resource_books = ResourceBook::where('book_id', $book_id)->get();
        $book_resources = [];
        foreach($resource_books as $rb){
            $resource = Resource::where('id', $rb->resource_id)->first();
            array_push($book_resources, $resource);
        }

		# 对于每个资源，检测其用户权限
		$resources = array();
        foreach ($common_resources as $resource){
            array_push($resources, $resource);
        }
		# 对每个资源进行用户权限检测
		foreach ($book_resources as $resource){
			if ($resource->checkUserValidation($user, $book_id)){
				array_push($resources, $resource);
			}
		}
		return $resources;
	}
}
