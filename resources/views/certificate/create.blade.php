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
                    {!! Form::open(["route"=>"cert.store", "method"=>"post", "files"=>true]) !!}
                    {{ Form::hidden("user_id", Auth::id()) }}
                    {{ Form::label("realname", "真实姓名:")}}
                    {{ Form::text("realname", null, ["class"=>"form-control"]) }}
                    {{ Form::label("workplace", "工作单位:", ["class"=>"form-spacing-top"]) }}
                    {{ Form::text("workplace", null, ["class"=>"form-control"]) }}
                    {{ Form::label("id_type", "认证类型:", ["class"=>"form-spacing-top"]) }}
                    @if($selection=="teacher" || $selection=="both")
                    {{ Form::radio("id_type", "teacher") }}<span>教师</span>
                    @endif
                    @if($selection=="author" || $selection=="both")
                    {{ Form::radio("id_type", "author") }}<span>作者</span>
                    @endif
                    <div id="teacher-input" style="display: none;">
                    <hr>
                    <p><strong>授课情况</strong></p>
                    {{ Form::label("course_name_1", "课程名称:")}}{{ Form::text("course_name_1", null, ["style"=>"form-control", "placeholder"=>"课程名称(必填)"])}}
                    {{ Form::label("course_name_1", "学生人数:")}}{{ Form::number("number_stud_1", null)}}<br>
                    {{ Form::label("course_name_2", "课程名称:")}}{{ Form::text("course_name_2", null, ["style"=>"form-control", "placeholder"=>"课程名称(可选)"])}}
                    {{ Form::label("course_name_2", "学生人数:")}}{{ Form::number("number_stud_2", null)}}<br>
                    {{ Form::label("course_name_3", "课程名称:")}}{{ Form::text("course_name_3", null, ["style"=>"form-control", "placeholder"=>"课程名称(可选)"])}}
                    {{ Form::label("course_name_3", "学生人数:")}}{{ Form::number("number_stud_3", null)}}
                    </div>

                    <hr>
                    {{ Form::label("img_upload", "上传照片:", ["class"=>"form-spacing-top"]) }}
                    <div id="teacher-input" style="display: none;"><small>请上传教师证、校网个人主页截图等可证明身份的图片</small></div>
                    <div id="author-input" style="display: none;"><small>请上传图书封面、样书照片等可证明身份的图片</small></div>
                    {{ Form::file("img_upload", ["class"=>"form-control"])}}
                    {{ Form::submit("提交", ["class"=>"btn btn-primary btn-block form-spacing-top"])}}
                    {!! Form::close() !!}
                @endif <!-- end top if -->
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($){
        if($("input[type='radio']").val()=="teacher")
            $("div#teacher-input").css("display", "");

        $("input[type='radio']").change(function(e){
            if($(this).val() == 'teacher'){
                $("#author-input").css("display", "none");
                $("div#teacher-input").css("display", "");
            }
            if($(this).val() == 'author'){
                $("div#teacher-input").css("display", "none");
                $("#author-input").css("display", "");
            }
        });
    });
    </script>

@endsection