<?php

namespace App\Http\Controllers\Admin\Forum;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request){
    	// TODO 统计值：评论量、阅读量、访问量、收藏量等的曲线图。
	    if($request->has('search')){
		    $search    = $request->get('search');
		    $materials = Material::search($search)->paginate(10);
	    }else{
		    $search    = "";
		    $materials = Material::orderBy('wechat_update_time','desc')->paginate(10);
	    }
	    return view('admin.forum.material.index',compact('materials','search'));
    }
}
