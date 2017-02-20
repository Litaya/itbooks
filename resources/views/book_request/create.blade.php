@extends('layouts.frame')

@section('title', "申请样书 | "."Book Name Here")

@section('content')
    <div class="container">
        <div class="row">
        <div class="col-md-8">
            {!! Form::open(["route"=>"bookreq.store", "method"=>"post"]) !!}
            {{ Form::hidden("user_id", 1) }}
            {{ Form::hidden("book_id", 1) }}
            {{ Form::label("message", "申请理由", ["class"=>"form-control"])}}
            {{ Form::textArea("message", null, ["class"=>"form-control"])}}
            {{ Form::submit("提交", ["class"=>"btn btn-success btn-block"])}}
            {!! Form::close() !!}
        </div>
        <div class="col-md-3 col-md-offset-1">
            Book Info
        </div>
    </div>

@endsection