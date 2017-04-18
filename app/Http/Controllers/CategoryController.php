<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Material;
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

	public function drop(Request $request,$id=null)
	{
		if(empty($id)){
			if($request->has('cate_id'))
				$id = $request->get('cate_id');
			else
				return 'failed';
		}
		Category::where('id', $id)->delete();
		Material::where('category_id', $id)->update(['category_id' => 0]);
		$request->session()->flash('forum_message','操作成功');
		$request->session()->flash('forum_status','success');
		return 'success';
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
