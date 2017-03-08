<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use App\Models\Conference;

class ConferenceAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->search){
            $search = $request->search;
            $conferences = Conference::where('name', 'like', "%$search%")
                                    ->orWhere('location', 'like', "%$search%")
                                    ->orWhere('host', 'like', "%$search%")
                                    ->orderBy('id', 'desc')
                                    ->paginate(15);
        }
        else $conferences = Conference::orderBy('id', 'desc')->paginate(15);

        return view('admin.conference.index')->withConferences($conferences);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.conference.create');
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
            "name"=>"required|max:50",
            "time"=>"required|date",
            "location"=>"required",
            "host"=>"required",
            "detail_url"=>"url"
        ]);

        $c = new Conference;
        $c->name = $request->name;
        $c->time = $request->time;
        $c->location = $request->location;
        $c->host = $request->host;
        $c->detail_url = $request->detail_url;
        $c->description = $request->description;
        $c->save();

        Session::flash('success', '创建会议成功');

        return redirect()->route('admin.conference.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $c = Conference::find($id);
        return view('admin.conference.show')->withConference($c);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $c = Conference::find($id);
        return view('admin.conference.edit')->withConference($c);
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
            "name"=>"required|max:50",
            "time"=>"required|date",
            "location"=>"required",
            "host"=>"required",
            "detail_url"=>"url"
        ]);

        $c = Conference::find($id);
        $c->name = $request->name;
        $c->time = $request->time;
        $c->location = $request->location;
        $c->host = $request->host;
        $c->detail_url = $request->detail_url;
        $c->description = $request->description;
        $c->update();

        Session::flash('success', '会议详情已更新');

        return redirect()->route('admin.conference.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $c = Conference::find($id);
        $c->delete();
        
        Session::flash('success', '删除会议成功');
        return redirect()->route('admin.conference.index');
    }
}
