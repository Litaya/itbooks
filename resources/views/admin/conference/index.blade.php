@extends('admin.layouts.frame')

@section('title', '会议管理')

@section('content')

    <div class="container">
        <div class="col-md-2 pull-right">
        <a href="{{route('admin.conference.create')}}"><button class="btn btn-default pull-right">新建会议</button></a>
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
                
                {{$conferences->links()}}

            </div>
            
        </div>
    </div>

@endsection