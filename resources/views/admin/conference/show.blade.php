@extends('admin.layouts.frame')

@section('title', '会议管理 | '.$conference->name)

@section('content')
<div class="container">
    <div class="row">
    <div class="col-md-8">
        <p><strong>{{$conference->name}}</strong></p>
        <ul>
        <li>时间: {{$conference->time}}</li>
        <li>地点: {{$conference->location}}</li>
        <li>主办方: {{$conference->host}}</li>
        <li>已报名人数: {{count($conference->participants)}}</li>
        <li>参会须知: {{$conference->detail_url}}</li>
        <li>会议介绍: <p>{{$conference->description}}</p></li>
        </ul>
    </div>

    <div class="col-md-3 col-md-offset-1">
        <p>创建时间: {{date('Y-m-d', strtotime($conference->created_at))}}</p>
        <p>修改时间: {{date('Y-m-d', strtotime($conference->updated_at))}}</p>
        <div class="row">
        <div class="col-md-6">
        <button class="btn btn-default" onclick="window.history.back();">返回会议列表</button>
        </div>
        <div class="col-md-6">
        <a href="{{route('admin.conference.edit', $conference->id)}}"><button class="btn btn-primary">编辑会议</button></a>
        </div>
        </div>
    </div>

    </div>
</div>
@endsection