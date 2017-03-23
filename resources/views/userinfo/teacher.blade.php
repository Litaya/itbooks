@extends("layouts.frame")

@section("title", "教师信息")

@section("content")

<div class="btn-group btn-group-justified" role="group" aria-label="...">
  <div class="btn-group" role="group">
    <a href="{{route('userinfo.basic')}}"><button type="button" class="btn btn-default">基本信息</button></a>
  </div>
  <div class="btn-group" role="group">
    <a href="{{route('userinfo.detail')}}"><button type="button" class="btn btn-default">详细信息</button></a>
  </div>
  <div class="btn-group" role="group">
    <a href="{{route('userinfo.teacher')}}"><button type="button" class="btn btn-success">教师附加信息</button></a>
  </div>
  <div class="btn-group" role="group">
    <a href="{{route('userinfo.author')}}"><button type="button" class="btn btn-default">作者附加信息</button></a>
  </div>
</div>
<br>

<!-- 学校，院系，职称，证明材料/照片，(所授课程，学生人数)x3 -->

<p><strong>教师信息</strong></p>

{!! Form::model($userinfo, ["id"=>"info-form", "route"=>"userinfo.teacher.save", "method"=>"post", "files"=>true]) !!}

<input id="sendrequest" type="hidden" name="sendrequest" value="false">

<small>
<div class="form-inline"><div class="form-group">
{{ Form::label("realname", "真实姓名") }}
{{ Form::text("realname", null, ["class"=>"form-control"])}}
</div></div>

<div class="form-inline"><div class="form-group">
{{ Form::label("workplace", "工作单位") }}
{{ Form::text("workplace", null, ["class"=>"form-control", "placeholder"=>"学校名称"])}}
</div></div>

<div class="form-inline"><div class="form-group">
{{ Form::label("department", "院系") }}
{{ Form::text("department", null, ["class"=>"form-control"]) }}
</div></div>


<div class="form-inline"><div class="form-group">
{{ Form::label("jobtitle", "职称") }}
{{ Form::text("jobtitle", null, ["class"=>"form-control"]) }}
</div></div>

<hr>

<div class="form-inline"><div class="form-group">
{{ Form::label("course_name_1", "课程名称1")}}{{ Form::text("course_name_1", null, ["class"=>"form-control", "placeholder"=>"课程名称"])}}
{{ Form::label("course_name_1", "学生人数1")}}{{ Form::number("number_stud_1", null,["class"=>"form-control", "placeholder"=>"课程人数"])}}
</div></div>
<div class="form-inline"><div class="form-group">
{{ Form::label("course_name_2", "课程名称2")}}{{ Form::text("course_name_2", null, ["class"=>"form-control", "placeholder"=>"课程名称"])}}
{{ Form::label("course_name_2", "学生人数2")}}{{ Form::number("number_stud_2", null, ["class"=>"form-control", "placeholder"=>"课程人数"])}}
</div></div>
<div class="form-inline"><div class="form-group">
{{ Form::label("course_name_3", "课程名称3")}}{{ Form::text("course_name_3", null, ["class"=>"form-control", "placeholder"=>"课程名称"])}}
{{ Form::label("course_name_3", "学生人数3")}}{{ Form::number("number_stud_3", null,["class"=>"form-control", "placeholder"=>"课程人数"])}}
</div></div>


{{ Form::label("img_upload", "上传图片材料", ["class"=>"form-spacing-top"]) }}
<small>(请上传教师证、校网个人主页截图等可供验证教师身份的图片)</small>
{{ Form::file("img_upload", ["class"=>"form-control"])}}


{{ Form::submit("保存", ["class"=>"btn btn-primary btn-block form-spacing-top"])}}
<button class="btn btn-primary btn-block form-spacing-top" onclick="save_and_apply()">保存并申请认证</button>
</small>
{!! Form::close() !!}

<script>
function save_and_apply()
{
  $("#sendrequest").val("true");
  $("#info-form").submit();
}
</script>

@endsection