<?php

namespace App\Http\Controllers\Wechat;

use App\Models\Category;
use App\Models\Material;
use App\Models\Wechat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WechatMaterialController extends Controller
{
	// 图文首页
	public function index(Request $request){
		$materials = Material::lists();
		$message   = "";
		if($request->has('search')){
			$message   = $request->get('search');
			$materials = Material::search($message)->simplePaginate(10);
		}
		$categories = Category::all();
		return view('material.index',compact('materials','message','categories'));
	}

	public function show(Request $request,$id){
		$material = Material::where('id',$id)->first();
		$material->reading_quantity ++;
		$material->save();
		return view('material.show',compact('material'));
	}

	public function cateMaterials(Request $request,$cate_id){
		if($request->has('search')) {
			$message   = $request->get('search');
			$materials = Material::search($message)->take(20)->get();
		}else{
			$materials = DB::table('materials')->select('id','display','url','cover_path','title','reading_quantity','wechat_update_time')->where('category_id',$cate_id)->orderBy("wechat_update_time",'desc')->take(20)->get();
		}
		$category   = Category::find($cate_id);
		$wechat_app = Wechat::getInstance()->getApp();
		$wechat_js  = $wechat_app->js;
		return view('material.cate_materials',compact('materials','category','wechat_js'));
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
