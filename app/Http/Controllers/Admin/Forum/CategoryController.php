<?php

namespace App\Http\Controllers\Admin\Forum;

use App\Http\Controllers\Controller;
use App\Libraries\WechatModules\Material;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){

    }

    public function forum_index(){
    	$categories = Category::all();
    	$counts     = array();
    	foreach ($categories as $category){
    		$count = Material::where('category_id',$category->id)->count();
    		$counts[$category->id] = $count;
	    }
    	return view('admin.forum.category',compact('categories','counts'));
    }
}