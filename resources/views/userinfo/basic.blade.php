@extends("layouts.frame")

@section("title", "基本信息")

@section("content")

@include("userinfo._sub_header")

<br>

<!-- 邮箱，真实姓名，手机号，角色，-->
<p><strong>基本信息</strong></p>

<small>
{!! Form::model($userinfo, ["route"=>"userinfo.basic.save", "method"=>"post"]) !!}


{{ Form::label("email", "邮箱") }}
{{ Form::email("email", null, ["class"=>"form-control"])}}

{{ Form::label("phone", "手机号") }}
{{ Form::text("phone", null, ["class"=>"form-control"])}}

{{ Form::label("role", "角色") }}
{{ Form::select('role', ['teacher' => '教师', 'student' => '学生', "stuff"=>"职员", "author"=>"作者", "other"=>"其他"], null, ['placeholder' => '选择您的身份', "class"=>"form-control", "disabled"=>$lockrole?"disabled":""]) }}
@if($lockrole)
<small>您已经在当前角色下提交或通过身份认证，不能改变角色，如有特殊需要请与管理员联系</small>
@endif


{{ Form::submit("保存", ["class"=>"btn btn-primary btn-block form-spacing-top"])}}

</small>
{!! Form::close() !!}

@endsection