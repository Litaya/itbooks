@extends('layouts.frame')

@section('title', '上传资源')

@section('content')


<div class="container">
    <div class="row">

        <style>
        label {
            margin-top: 10px;
        }
        .form-spacing-top{
            margin-top: 18px;
        }
        </style>
        <div class="panel panel-default">
        <div class="panel-heading">
        <div class="row">
            <div class="col-md-7">
            上传资源
            </div>
            <div class="col-md-1 col-md-offset-3">
                <button class="btn btn-sm btn-primary btn-block" onclick="window.history.back()">返回</button></a>
            </div>
        </div>
        </div>

        <div class="panel-body">
        <p><strong>{{$resource->title}}</strong></p>
        <p>资源描述: {{$resource->description}}</p>
        <hr>
        <p><small>下载需要积分: {{$resource->credit}}</small></p>
        <p><small>文件名: {{substr($resource->file_upload, strpos($resource->file_upload, '/')+1)}}</small></p>
        {!! Form::open(["route"=>["resource.download", $resource->id], "method"=>"post"]) !!}
        <div class="col-md-4 col-md-offset-2">
        {{ Form::submit("下载", ["class"=>"btn btn-success btn-block form-spacing-top"]) }}
        </div>
        {!! Form::close() !!}
        <div class="col-md-4">
        <a href="{{route('resource.index')}}">
            <button class="btn btn-default btn-block form-spacing-top">返回首页</button>
        </div>

    </div>
</div>



@endsection