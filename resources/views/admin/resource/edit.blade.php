@extends('admin.layouts.frame')

@section('title', '修改资源信息')

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
            <div class="col-md-12">
            修改资源信息
            <button class="btn btn-xs btn-primary pull-right" onclick="window.history.back();">返回</button></a>
            </div>
        </div>
        </div>

        <div class="panel-body">
            {!! Form::open(["route"=>["admin.resource.update", $resource->id], "method"=>"POST", "files"=>false]) !!}
            {{ Form::label("title", "标题:") }} {{ Form::text("title", $resource->title, ["class"=>"form-control"]) }}
            {{ Form::label("title", "书号:") }} {{ Form::text("book_isbn", empty($resource->ownerBook)?"":$resource->ownerBook->isbn, ["class"=>"form-control"]) }}
            {{ Form::label("description", "资源描述:") }}
            {{ Form::textarea("description", $resource->description, ["class"=>"form-control", "placeholder"=>"如果使用网盘链接，请记得填写分享密码、解压密码等重要信息"]) }}
            {{ Form::label("role", "下载权限:") }}
            {{ Form::checkbox("role_teacher", "TEACHER", false) }}教师
            {{ Form::checkbox("role_teacher_with_order", "TEACHER_WITH_ORDER", false) }}上传过本书订购证明的教师
            {{ Form::checkbox("role_user", "USER", false) }}普通用户
            <br>
            {{ Form::label("credit", "消耗积分:") }} {{ Form::number("credit", $resource->credit, ["class"=>"form-control"]) }}
            {{ Form::label("file_upload", "文件链接")}} {{ Form::url("file_upload", $resource->file_upload, ["class"=>"form-control"]) }}

            <div class="col-md-4">
                {{ Form::submit("保存", ["class"=>"btn btn-success btn-block form-spacing-top"]) }}
            </div>
            {!! Form::close() !!}
            <div class="col-md-4">
                <a href="{{route('admin.resource.index')}}">
                    <button class="btn btn-default btn-block form-spacing-top">返回首页</button>
                </a>
            </div>
        </div>

    </div>
</div>



@endsection