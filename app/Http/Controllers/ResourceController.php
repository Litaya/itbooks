<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Book;
use App\Models\Resource;

use App\Helpers\FileHelper;

use Auth;
use Session;

class ResourceController extends Controller
{

    public function postDownload(Request $request){
        return "页面未准备好 - 2017/02/24";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('resource.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('resource.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "file_upload" => "required",
            "title" => "required|min:1|max:30",
            "credit" => "required|integer",
        ]);

        $access_role = [];
        if($request->role_author) array_push($access_role, 'AUTHOR');
        if($request->role_teacher) array_push($access_role, 'TEACHER');
        if($request->role_user) array_push($access_role, 'USER');

        if(count($access_role) > 0){
            $res = new Resource;
            $res->title = $request->title;
            $res->owner_user_id = Auth::id();
            $res->file_upload = FileHelper::saveResourceFile($request->file_upload);
            $res->access_role = implode('|', $access_role);
            $res->description = $request->description;
            $res->credit = $request->credit;
            $res->type = ($request->file_upload)->getClientOriginalExtension();
            if(!empty($request->book_id) and Book::find($requst->book_id))
                $res->owner_book_id = $request->book_id;
            $res->save();

            Session::flash('success', '上传资源成功');
        } else {
            return redirect()->back()->withErrors(['用户角色至少勾选一项']);
        }



        return redirect()->route('resource.show', $res->id);
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
        return view('resource.show')->withResource($res);
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
        return view('resource.edit')->withResource($res);
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

        $res = Resource::find($id);
        $res->owner_user_id = Auth::id();
        $res->file_upload = FileHelper::saveResourceFile($request->file_upload);
        $res->access_role = implode('|', $request->access_role);
        $res->description = $request->description;
        $res->credit = $request->credit;
        $res->type = ($request.file_upload)->getClientOriginalExtension();
        if(!empty($request->book_id) and Book::find($requst->book_id))
            $res->owner_book_id = $request->book_id;
        $res->update();
            
        Session::flash('success', '资源信息修改成功');

        return redirect()->route('resource.show', $res->id);
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
        return redirect()->route('resource.index');
    }
}
