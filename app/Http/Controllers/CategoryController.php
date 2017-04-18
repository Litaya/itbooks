<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
	public function index(){
	}

	/**
	 * web, post
	 * @param Request $request
	 * @return bool
	 */
	public function create(Request $request){
		$this->validate($request,[
			'cate_name' => 'required'
		]);
		$cate_name = $request->get('cate_name');
		$cate = Category::where('name',$cate_name)->get();

		if( Auth::check() && sizeof($cate) == 0 ){
			$user_id = Auth::user()->id;
			$admin   = Admin::where('user_id',$user_id)->get();
			if(sizeof($admin) > 0)
				$user_id = 0;
			# 如果创建人是管理员的话， category的user_id置为0，便于统一管理
			Category::create([
				'name'    => $cate_name,
				'user_id' => $user_id
			]);
			return 'success';
		}
		return 'failed';
	}

	public function show(){
	}

	public function drop(){
		// TODO 一定要把相关联的素材的分类都改为未分类 0
	}

	// for api
	public function getAll(){
		$categories = Category::all();
		return json_encode($categories);
	}

	public function cateExist(Request $request){
		$cates = [];
		if($request->has('cate_name')){
			$cate_name = $request->get('cate_name');
			$cates = Category::where('name',$cate_name)->get();
		}else if($request->has('cate_id')){
			$cate_id = $request->get('cate_id');
			$cates = Category::where('id',$cate_id)->get();
		}
		if(sizeof($cates)>0) return 'success';
		return 'failed';
	}
}
