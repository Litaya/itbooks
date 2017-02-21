@extends('layouts.frame')

@section('title', "身份认证")

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-left">身份认证</h2>
                <hr>
                @if($selection=="exist")
                    您有未完成的审批，审批进度为<strong>正在审核</strong>，请不要重复申请！
                @else
                    {!! Form::open(["route"=>"cert.store", "method"=>"post"]) !!}
                    {{ Form::hidden("user_id", Auth::id()) }}
                    {{ Form::label("realname", "真实姓名:")}}
                    {{ Form::text("realname", null, ["class"=>"form-control"]) }}
                    {{ Form::label("id_number", "工号:", ["class"=>"form-spacing-top"]) }}
                    {{ Form::text("id_number", null, ["class"=>"form-control"]) }}
                    {{ Form::label("id_type", "认证类型:", ["class"=>"form-spacing-top"]) }}
                    @if($selection=="teacher" || $selection=="both")
                    {{ Form::radio("id_type", "teacher") }}{{ Form::label("id_type", "教师") }}
                    @endif
                    @if($selection=="author" || $selection=="both")
                    {{ Form::radio("id_type", "author") }}{{ Form::label("id_type", "作者") }}
                    @endif
                    <hr>
                    {{ Form::label("img_upload", "上传证件:", ["class"=>"form-spacing-top"]) }}
                    {{ Form::file("img_upload", ["class"=>"form-control"])}}
                    {{ Form::submit("提交", ["class"=>"btn btn-primary btn-block form-spacing-top"])}}
                    {!! Form::close() !!}
                @endif <!-- end top if -->
            </div>
        </div>
    </div>

@endsection