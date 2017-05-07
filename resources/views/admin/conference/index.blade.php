@extends('admin.layouts.frame')

@section('title', '会议管理')

@section('content')

    <div class="container">
        <div class="row">
            <!-- SEARCH BAR -->
            <div class="col-md-10"> 
            {!! Form::open(["route"=>"admin.conference.index", "method"=>"GET"]) !!}
            {{ Form::text("search", null, ["placeholder"=>"会议、地点、主办方..."]) }}
            {{ Form::submit("搜索") }}
            {!! Form::close() !!}
            </div>
            <!-- END SEARCH BAR -->

            <div class="col-md-2">
            <a href="{{route('admin.conference.create')}}"><button class="btn btn-default pull-right">新建会议</button></a>
            </div>
        </div>
        <div class="row">
            <div class="panel">
                <table class="table">
                <thead>
                <th>会议名</th>
                <th>时间</th>
                <th>地点</th>
                <th>主办方</th>
                <th>已报名人数</th>
                <th></th>
                </thead>
                <tbody>
                @foreach($conferences as $c)
                <tr>
                <td>{{$c->name}}</td>
                <td>{{$c->time}}</td>
                <td>{{$c->location}}</td>
                <td>{{$c->host}}</td>
                <td>{{count($c->participants)}}</td>
                <td><a href="{{route('admin.conference.show', $c->id)}}"><button class="btn btn-primary btn-xs">详情</button></a></td>
                </tr>
                @endforeach
                </tbody>
                </table>
                <div>
                {!! $conferences->appends(Input::except('page'))->links() !!}
                </div>
            </div>
            
        </div>
    </div>

@endsection