<?php

namespace App\Http\Controllers\Admin\Forum;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){

    }

    public function forum_index(){
    	$categories = Category::all();
    	return view('admin.forum.category',compact('categories'));
    }
}