<?php

namespace App\Http\Controllers\Wechat;

use App\Models\Material;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatMaterialController extends Controller
{
    // 图文首页
	public function index(Request $request){
		$materials = Material::lists();
		$message   = "";
		if($request->has('search')){
			$message   = $request->get('search');
			$materials = Material::search($message);
		}
		return view('material.index',compact('materials','message'));
	}

	public function show(Request $request,$id){
		$material = Material::where('id',$id)->first();
//		$material->reading_quantity ++;
//		$material->save();
		return view('material.show',compact('material'));
	}

	// 图文详情页
	public function materialIndex(){}

	// 添加评论
	public function comment(){}

	// 收藏文章
	public function storeMaterial(){}

	// 分享文章
	public function shareMaterial(){}
}
