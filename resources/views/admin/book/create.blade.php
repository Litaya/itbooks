@extends('admin.layouts.frame')
@section('title', '图书管理')
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
            图书信息
            </div>
            <div class="col-md-1 col-md-offset-3">
                <a href="{{route("admin.book.index")}}"><button class="btn btn-sm btn-primary btn-block">返回</button></a>
            </div>
        </div>
        </div>

        <div class="panel-body">
        {!! Form::open(["route"=>["admin.book.store"], "method"=>"POST", "files"=>true]) !!}
        {{ Form::label("name", "书名:") }} {{ Form::text("name", null, ["class"=>"form-control"]) }}
        {{ Form::label("authors", "作者:") }} {{ Form::text("authors", null, ["class"=>"form-control"]) }}
        {{ Form::label("isbn", "ISBN:") }} {{ Form::text("isbn", null, ["class"=>"form-control"]) }}
        {{ Form::label("price", "价格:") }} {{ Form::text("price", null, ["class"=>"form-control"]) }}
        {{ Form::label("type", "类别:") }} 
            {{ Form::radio("type", 0, true) }} 未知
            {{ Form::radio("type", 1) }} 教辅
            {{ Form::radio("type", 2) }} 非教辅
        </br>
        <!-- IF USER HAS 'ALL_DEPARTMENT_CRUD' PERMISSION -->
        {{ Form::label("department_id", "部门:") }} {{ Form::select("department_id", $departments, null, ["placeholder"=>"选择部门", "class"=>"form-control"]) }}
        <!-- END IF -->
        {{ Form::label("product_number", "出版号:") }} {{ Form::text("product_number", null, ["class"=>"form-control"]) }}
        {{ Form::label("publish_time", "出版时间:")}} {{Form::text("publish_time", null, ["class"=>"form-control"]) }}
        {{ Form::label("editor_name", "编辑:") }} {{ Form::text("editor_name", null, ["class"=>"form-control"]) }}
        {{ Form::label("weight", "权重:")}} (不填默认为0)
        {{ Form::number("weight", 0, ["class"=>"form-control"]) }}
        {{ Form::label("img_upload", "修改配图:")}}  (若不修改请不要添加此项)
        {{ Form::file("img_upload", ["class"=>"form-control"]) }}
        <div class="col-md-4">
        {{ Form::submit("保存", ["class"=>"btn btn-success btn-block form-spacing-top"]) }}
        </div>
        {!! Form::close() !!}
        </div>

    </div>
</div>


@endsection