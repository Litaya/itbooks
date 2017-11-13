@extends('admin.layouts.frame')

@section('title', '用户管理')

@section('content')
    {!! Form::open(["route"=>"admin.test.store_express", "method"=>"post", "files"=>true, 'class'=>'form-inline']) !!}
    {{ Form::file("express_file", ["class"=>"form-control form-spacing-top"])}}
    {{ Form::submit("导入发行单", ["class"=>"btn btn-primary form-spacing-top"])}}
    {!! Form::close() !!}
@stop