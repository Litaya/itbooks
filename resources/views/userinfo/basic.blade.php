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
<select name="role" "class"="form-control">
    <option value="teacher">教师</option>
    <option value="student">学生</option>
    <option value="staff">职员</option>
    <option value="author">作者</option>
    <option value="other">其他</option>
</select><br>


{{ Form::submit("保存", ["class"=>"btn btn-primary btn-block form-spacing-top"])}}

</small>
{!! Form::close() !!}

@endsection