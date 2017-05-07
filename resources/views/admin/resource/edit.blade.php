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
        {!! Form::model($res, ["route"=>["resource.update"], "method"=>"POST"]) !!}
        {{ Form::label("title", "标题:") }} {{ Form::text("title", null, ["class"=>"form-control"]) }}
        {{ Form::label("description", "资源描述:") }} {{ Form::text("description", null, ["class"=>"form-control"]) }}
        {{ Form::label("role", "下载权限:") }} 
            {{ Form::checkbox("role", "TEACHER") }}教师
            {{ Form::checkbox("role", "AUTHOR") }}出版社作者
            {{ Form::checkbox("role", "USER") }}普通用户
        <br>
        {{ Form::label("credit", "消耗积分:") }} {{ Form::number("credit", null, ["class"=>"form-control"]) }}

        <div class="col-md-4">
        {{ Form::submit("保存修改", ["class"=>"btn btn-success btn-block form-spacing-top"]) }}
        </div>
        {!! Form::close() !!}
        <div class="col-md-4">
        <a href="{{route('resource.index')}}">
            <button class="btn btn-default btn-block form-spacing-top">放弃修改</button>
        </a>
        </div>

    </div>
</div>



@endsection