@extends('admin.layouts.frame')

@section('title', '上传资源')

@section('content')
    <div class="container">
        <style>
            label {
                margin-top: 10px;
            }
            .form-spacing-top{
                margin-top: 18px;
            }
        </style>
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <h3 class="col-md-12">
                            上传资源
                            <button class="btn btn-xs btn-primary" onclick="window.history.back()">返回</button></a>
                        </h3>
                        <p class="col-md-12">目前仅支持上传链接类资源</p>
                    </div>
                </div>

                <div class="panel-body">
                    {!! Form::open(["route"=>["admin.resource.store"], "method"=>"POST", "files"=>false]) !!}
                    {{ Form::label("title", "标题:") }} {{ Form::text("title", null, ["class"=>"form-control"]) }}
                    {{ Form::label("title", "书号:") }} {{ Form::text("book_isbn", null, ["class"=>"form-control"]) }}
                    {{ Form::label("description", "资源描述:") }}
                    {{ Form::textarea("description", null, ["class"=>"form-control", "placeholder"=>"如果使用网盘链接，请记得填写分享密码、解压密码等重要信息"]) }}
                    {{ Form::label("role", "下载权限:") }}
                    {{ Form::checkbox("role_teacher", "TEACHER", true) }}教师
                    {{ Form::checkbox("role_teacher_with_order", "TEACHER_WITH_ORDER", true) }}上传过本书订购证明的教师
                    {{ Form::checkbox("role_user", "USER", true) }}普通用户
                    <br>
                    {{ Form::label("credit", "消耗积分:") }} {{ Form::number("credit", null, ["class"=>"form-control"]) }}
                    {{ Form::label("file_upload", "文件链接")}} {{ Form::url("file_upload", null, ["class"=>"form-control"]) }}

                    <div class="col-md-4">
                        {{ Form::submit("保存", ["class"=>"btn btn-success btn-block form-spacing-top"]) }}
                    </div>
                    {!! Form::close() !!}
                    <div class="col-md-4">
                        <a href="{{route('resource.index')}}">
                            <button class="btn btn-default btn-block form-spacing-top">返回首页</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection