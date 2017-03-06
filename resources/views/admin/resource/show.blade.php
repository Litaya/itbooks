@extends('admin.layouts.frame')

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
            <div class="col-md-1 col-md-offset-4">
                <button class="btn btn-sm btn-primary btn-block" onclick="window.history.back()">返回</button></a>
            </div>
        </div>
        </div>

        <div class="panel-body">
        <p><strong>{{$resource->title}}</strong></p>
        <p>资源描述: {{$resource->description}}</p>
        <hr>
        <p><small>下载需要积分: {{$resource->credit}}</small></p>
        <!-- p><small>文件名: {{substr($resource->file_upload, strpos($resource->file_upload, '/')+1)}}</small></p-->
        <div class="col-md-4">
        <a href="{{$resource->file_upload}}"><button class="btn btn-success btn-block form-spacing-top"]>下载资源</button></a>
        </div>
        <div class="col-md-4">
            {!! Form::open(["route"=>["admin.resource.destroy", $resource->id], "method"=>"delete"]) !!}
            {{ Form::submit("删除资源", ["class"=>"btn btn-danger btn-block form-spacing-top"]) }}
            {!! Form::close() !!}
        </div>
        <div class="col-md-4">
            <a href="{{route('admin.resource.index')}}">
            <button class="btn btn-default btn-block form-spacing-top">返回列表</button>
            </a>
        </div>

    </div>
</div>

<!-- OLD DOWNLOAD FORM
            {!! Form::open(["route"=>["admin.resource.download", $resource->id], "method"=>"post"]) !!}
            {{ Form::submit("下载资源", ["class"=>"btn btn-success btn-block form-spacing-top"]) }}
            {!! Form::close() !!}
-->

@endsection