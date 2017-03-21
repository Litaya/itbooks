@extends("layouts.frame")

@section("title", "补充信息")

@section("content")

<!-- ------------------------- TEACHER CERTIFICATION MISSING ITEMS ------------------------------- -->

@if($certtype == "teacher")

{!! Form::model($userinfo, ["route"=>"userinfo.missing.save", "method"=>"post", "files"=>true]) !!}

<small>
@if( in_array("realname", $missing)  )
{{ Form::label("realname", "真实姓名") }}
{{ Form::text("realname", null, ["class"=>"form-control"])}}
@endif


@if( in_array("workplace", $missing)  )
{{ Form::label("workplace", "工作单位（学校）") }}
{{ Form::text("workplace", null, ["class"=>"form-control"])}}
@endif

@if( in_array("department", $missing)  )
{{ Form::label("department", "院系", ["class"=>"form-spacing-top"]) }}
{{ Form::text("department", null, ["class"=>"form-control"]) }}
@endif


@if( in_array("jobtitle", $missing)  )
{{ Form::label("jobtitle", "职称", ["class"=>"form-spacing-top"]) }}
{{ Form::text("jobtitle", null, ["class"=>"form-control"]) }}
@endif


@if( in_array("course", $missing)  )
{{ Form::label("course_name_1", "课程名称:")}}{{ Form::text("course_name_1", null, ["class"=>"form-control", "placeholder"=>"课程名称"])}}
{{ Form::label("course_name_1", "学生人数:")}}{{ Form::number("number_stud_1", null,["class"=>"form-control", "placeholder"=>"课程人数"])}}<br>
{{ Form::label("course_name_2", "课程名称:")}}{{ Form::text("course_name_2", null, ["class"=>"form-control", "placeholder"=>"课程名称"])}}
{{ Form::label("course_name_2", "学生人数:")}}{{ Form::number("number_stud_2", null, ["class"=>"form-control", "placeholder"=>"课程人数"])}}<br>
{{ Form::label("course_name_3", "课程名称:")}}{{ Form::text("course_name_3", null, ["class"=>"form-control", "placeholder"=>"课程名称"])}}
{{ Form::label("course_name_3", "学生人数:")}}{{ Form::number("number_stud_3", null,["class"=>"form-control", "placeholder"=>"课程人数"])}}
@endif


@if( in_array("img_upload", $missing)  )
{{ Form::label("img_upload", "上传图片材料:", ["class"=>"form-spacing-top"]) }}
<small>请上传教师证、校网个人主页截图等可供验证教师身份的图片</small>
{{ Form::file("img_upload", ["class"=>"form-control"])}}
@endif

{{ Form::submit("发起申请", ["class"=>"btn btn-primary btn-block form-spacing-top"])}}

</small>
{!! Form::close() !!}

@endif

<!-- ------------------------- END TEACHER ------------------------ -->


<!-- ------------------------- AUTHOR CERTIFICATION MISSING ITEMS ------------------------------- -->


@if($certtype == "author")
{!! Form::model($userinfo, ["route"=>"userinfo.missing.save", "method"=>"post", "files"=>true]) !!}

<small>
@if( in_array("realname", $missing)  )
{{ Form::label("realname", "真实姓名") }}
{{ Form::text("realname", null, ["class"=>"form-control"])}}
@endif

@if( in_array("workplace", $missing)  )
{{ Form::label("workplace", "工作单位") }}
{{ Form::text("workplace", null, ["class"=>"form-control"])}}
@endif

@if( in_array("img_upload", $missing)  )
{{ Form::label("img_upload", "上传图片材料:", ["class"=>"form-spacing-top"]) }}
<small>请上传图书封面、样书照片等可供验证作者身份的图片</small></div>
{{ Form::file("img_upload", ["class"=>"form-control"])}}
@endif

{{ Form::submit("发起申请", ["class"=>"btn btn-primary btn-block form-spacing-top"])}}

</small>
{!! Form::close() !!}

@endif


@endsection