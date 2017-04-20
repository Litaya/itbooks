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

	public function updateCategory(Request $request){
		$this->validate($request,[
			'material_id' => 'required',
			'category_id' => 'required'
		]);
		Material::where('id',$request->get('material_id'))->update(['category_id'=>$request->get('category_id')]);

	}

	// 评论详情页
	public function comments(){}

	// 通过评论
	public function passComment(){}

	// 删除评论
	public function dropComment(){}


}
