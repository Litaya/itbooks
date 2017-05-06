<?php

namespace App\Http\Controllers;

use App\Models\Wechat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function imgUpload(){
    	$app = Wechat::getInstance()->getApp();
    	$js  = $app->js;
    	return view('test.imgupload',compact('js'));
    }
    public function saveImage(Request $request){
	    $app = Wechat::getInstance()->getApp();
	    $js  = $app->js;
    	Log::info($request->img_media_id);
    	return view('test.imgupload', compact('js'));
    }
}
