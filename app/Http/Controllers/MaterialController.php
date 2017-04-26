<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
	public function getCateMaterials(Request $request){
		if($request->has('cate_id')){
			$page      = $request->get('page');
			$per_page  = $request->get('per_page');
			$cate_id   = $request->get('cate_id');
			$materials = DB::table('materials')->select('id','title','wechat_update_time')->where('category_id',$cate_id)->orderBy("wechat_update_time",'desc')->skip(((int)$page-1)*((int)$per_page))->take($per_page)->get();
			return json_encode($materials);
		}
		return "";
	}
}
