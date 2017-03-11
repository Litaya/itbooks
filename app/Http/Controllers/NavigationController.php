<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Observer;
use Auth;

class NavigationController extends Controller
{
    // 用户点击的URL通过这里中转，以记录用户行为
    public function navigate(Request $request){
        $request->user = Auth::user();
        Observer::observe("navigate", $request);
        return redirect()->to($request->url);
    }

}
