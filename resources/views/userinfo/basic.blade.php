@extends("layouts.frame")

@section("title", "基本信息")

@section("content")

<div class="btn-group btn-group-justified" role="group" aria-label="...">
  <div class="btn-group" role="group">
    <a href="{{route('userinfo.basic')}}"><button type="button" class="btn btn-success">基本信息</button></a>
  </div>
  <div class="btn-group" role="group">
    <a href="{{route('userinfo.detail')}}"><button type="button" class="btn btn-default">详细信息</button></a>
  </div>
  <div class="btn-group" role="group">
    <a href="{{route('userinfo.teacher')}}"><button type="button" class="btn btn-default">教师附加信息</button></a>
  </div>
  <div class="btn-group" role="group">
    <a href="{{route('userinfo.author')}}"><button type="button" class="btn btn-default">作者附加信息</button></a>
  </div>
</div>

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
{{ Form::select('role', ['teacher' => '教师', 'student' => '学生', "stuff"=>"职员", "author"=>"作者", "other"=>"其他"], null, ['placeholder' => '选择您的身份', "class"=>"form-control"]) }}

{{ Form::submit("保存", ["class"=>"btn btn-primary btn-block form-spacing-top"])}}

</small>
{!! Form::close() !!}

@endsection