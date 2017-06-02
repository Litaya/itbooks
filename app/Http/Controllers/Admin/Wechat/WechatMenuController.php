<?php

namespace App\Http\Controllers\Admin\Wechat;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Wechat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WechatMenuController extends Controller
{
    public function index(){
    	$menus = Menu::orderBy('status','desc')->orderBy('created_at')->get();
    	$menus_new = [];
	    foreach ($menus as $menu) {
		    $menu->json = json_decode($menu->json);
		    array_push($menus_new,$menu);
    	}
    	return view('admin.wechat.menu.index')->withMenus($menus_new);
    }
    public function apply(Request $request, $id){
    	Menu::where('status',1)->update(['status'=>0]);
    	Menu::where('id',$id)->update(['status'=>1]);
    	$menu = Menu::where('id',$id)->first();
    	$app = Wechat::getInstance()->getApp();
    	$app->add(json_decode($menu->json, true));
		$request->session()->flash('wechat_message','已成功应用菜单');
		$request->session()->flash('wechat_status','success');
		return 'success';
    }
    public function detail(Request $request, $id){
    	$menu = Menu::where('id',$id)->first();
	    $menu = $this->resolveJson($menu);
	    return view('admin.wechat.menu.detail')->withMenu($menu);
    }
    public function create(){
    	return view('admin.wechat.menu.create');
    }
    public function edit(Request $request, $id){
	    $menu = Menu::where('id',$id)->first();
	    Log::info($menu->json);
    	return view('admin.wechat.menu.edit')->withMenu($menu);
    }
    public function saveEdit(Request $request, $id){
	    $this->validate($request,['menu'=>'required','title'=>'required']);
	    $menu = Menu::where('id',$id)->first();
	    $menu->json = json_encode($request->get('menu'));
	    $menu->title = $request->get('title');
	    $menu->save();
	    $request->session()->flash('wechat_message','保存成功');
	    $request->session()->flash('wechat_status','success');
    	return $menu->id;
    }
	public function drop(Request $request, $id){
    	Menu::where('id',$id)->delete();
		$request->session()->flash('wechat_message','已成功删除菜单模板');
		$request->session()->flash('wechat_status','success');
		return 'success';
	}

	/**
	 * 返回保存的menu的id
	 */
	public function postSave(Request $request){
		$this->validate($request,['menu'=>'required','title'=>'required']);
		$button = $request->get('menu');
		$app = Wechat::getInstance()->getApp();
		$app->add($button);
		$menu = Menu::create([
			'title' => $request->get('title'),
			'json'  => json_encode($button),
			'status' => 0
		]);
		return $menu->id;
	}

	private function resolveJson($menu){
		$menu->json = json_decode($menu->json);
		return $menu;
	}
}
