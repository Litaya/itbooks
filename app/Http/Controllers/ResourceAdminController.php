<?php

namespace App\Http\Controllers;

use App\Models\ResourceBook;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Resource;

use App\Helpers\FileHelper;

use Auth;
use Session;
use DB;

class ResourceAdminController extends Controller
{

	public function postDownload(Request $request){
		return "页面未准备好 - 2017/02/24";
		// 管理员下载资源不消耗积分
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		if($request->search){
			$search = $request->search;
			$resources = DB::table('resource')
				->join('user', 'resource.owner_user_id', '=', 'user.id')
				->select('resource.*')
				->where('resource.title', 'like', "%$search%")
				->orWhere('user.username', 'like', "%$search%")
				->paginate(20);

			for($i=0; $i<count($resources); $i++){
				$r = (new Resource)->newFromBuilder($resources[$i]);
				$resources[$i] = $r;
			}
		}
		else $resources = Resource::paginate(20);
		return view('admin.resource.index')->withResources($resources);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('admin.resource.create');
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			"file_upload" => "required",
			"title" => "required|min:1|max:30",
		]);

		# 判断用户是否已选权限角色，如果没选，返回选择
		$access_role = [];
		if ($request->role_teacher_with_order) array_push($access_role, 'TEACHER_WITH_ORDER');
		if ($request->role_teacher) array_push($access_role, 'TEACHER');
		if ($request->role_user) array_push($access_role, 'USER');
		if (count($access_role) == 0) return redirect()->back()->withErrors(['用户角色至少勾选一项']);

		# 判断用户是否输入了书籍isbn
		$owner_book_id = null;
		# 如果未输入，则本资源对应所有书籍
		if (empty($request->book_isbn)){
			$owner_book_id = 0;
		}else{ # 如果输入了，则判断输入的isbn是否全都有效
			$book_isbns = $request->book_isbn;
			$book_isbns = explode('|', $book_isbns);
			$books = [];
			$invalid_books = [];
			foreach ($book_isbns as $book_isbn){
				$book_isbn = trim($book_isbn);
				$book = Book::where('isbn','like', '%'.$book_isbn)->first();
				if (empty($book)){
					array_push($invalid_books, $book_isbn);
				}else{
					array_push($books, $book);
				}
			}
			# 如果存在无效isbn，则返回充填
			if (count($invalid_books) > 0){
				$error_message = '以下isbn无效：';
				foreach ($invalid_books as $book_isbn){
					$error_message .= $book_isbn." ";
				}
				return redirect()->back()->withErrors([$error_message]);
			}else{ # 否则，设置 $owner_book_id为1，代表有本资源对应多本书籍，在后面保存资源后 获取resource_id，将其保存如 resource_book 中
				$owner_book_id = 1;
			}
		}

		$res = new Resource;
		$res->title = $request->title;
		$res->owner_user_id = Auth::id();
		// $res->file_upload = FileHelper::saveResourceFile($request->file_upload);
		$res->file_upload = $request->file_upload;
		$res->access_role = implode('|', $access_role);
		$res->description = $request->description;
		$res->credit = $request->credit;
		if (empty($request->credit))
			$res->credit = 0;
		$res->type = "url"; //($request->file_upload)->getClientOriginalExtension();
		$res->owner_book_id = $owner_book_id;
		$res->save();

		# 将每个resource，book 的对应关系保存到 resource_book 中
		if ($owner_book_id == 1){
			foreach ($books as $book){
				$resource_book = new ResourceBook;
				$resource_book->resource_id = $res->id;
				$resource_book->book_id = $book->id;
				$resource_book->save();
        	}
		}

		Session::flash('success', '上传资源成功');

		return redirect()->route('admin.resource.show', $res->id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$res = Resource::find($id);
		return view('admin.resource.show')->withResource($res);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$res = Resource::find($id);
		$books = $res->books();
		$isbns = [];
		foreach ($books as $book){
			array_push($isbns, $book->isbn);
		}
		return view('admin.resource.edit')->withResource($res)->withIsbns($isbns);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$this->validate($request, [
			"file_upload" => "required",
			"title" => "required|min:1|max:30",
			"credit" => "required|integer",
		]);

		$access_role = [];
		if ($request->role_teacher_with_order) array_push($access_role, 'TEACHER_WITH_ORDER');
		if ($request->role_teacher) array_push($access_role, 'TEACHER');
		if ($request->role_user) array_push($access_role, 'USER');
		if (count($access_role) == 0) return redirect()->back()->withErrors(['用户角色至少勾选一项']);

		$res = Resource::find($id);

		# 判断用户是否输入了书籍isbn
		$owner_book_id = null;
		# 如果未输入，则本资源对应所有书籍
		if (empty($request->book_isbn)){
			$owner_book_id = 0;
			ResourceBook::where('resource_id', $res->id)->delete();
		}else{ # 如果输入了，则判断输入的isbn是否全都有效
			$book_isbns = $request->book_isbn;
			$book_isbns = explode('|', $book_isbns);
			$books = [];
			$invalid_books = [];
			foreach ($book_isbns as $book_isbn){
				$book_isbn = trim($book_isbn);
				$book = Book::where('isbn','like', '%'.$book_isbn)->first();
				if (empty($book)){
					array_push($invalid_books, $book_isbn);
				}else{
					array_push($books, $book);
				}
			}
			# 如果存在无效isbn，则返回充填
			if (count($invalid_books) > 0){
				$error_message = '以下isbn无效：';
				foreach ($invalid_books as $book_isbn){
					$error_message .= $book_isbn." ";
				}
				return redirect()->back()->withErrors([$error_message]);
			}else{ # 否则，设置 $owner_book_id为1，代表有本资源对应多本书籍，在后面保存资源后 获取resource_id，将其保存如 resource_book 中
				$owner_book_id = 1;
			}
		}

		$res->title = $request->title;
		$res->owner_user_id = Auth::id();
		// $res->file_upload = FileHelper::saveResourceFile($request->file_upload);
		$res->file_upload = $request->file_upload;
		$res->access_role = implode('|', $access_role);
		$res->description = $request->description;
		$res->credit = $request->credit;
		if (empty($request->credit))
			$res->credit = 0;
		$res->type = "url"; //($request->file_upload)->getClientOriginalExtension();
		$res->owner_book_id = $owner_book_id;

		if( $owner_book_id == 1){
			ResourceBook::where('resource_id',$res->id)->delete();
			foreach ($books as $book){
				$resource_book = new ResourceBook;
				$resource_book->resource_id = $res->id;
				$resource_book->book_id = $book->id;
				$resource_book->save();
			}
		}

		$res->update();
		Session::flash('success', '资源信息修改成功');

		return redirect()->route('admin.resource.show', $res->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$res = Resource::find($id);
		$res->delete();

		Session::flash('success', '删除资源成功');
		return redirect()->route('admin.resource.index');
	}
}
