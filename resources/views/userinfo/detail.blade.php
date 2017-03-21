
@extends("layouts.frame")

@section("title", "详细资料")

@section("content")

<div class="btn-group btn-group-justified" role="group" aria-label="...">
  <div class="btn-group" role="group">
    <a href="{{route('userinfo.basic')}}"><button type="button" class="btn btn-default">基本信息</button></a>
  </div>
  <div class="btn-group" role="group">
    <a href="{{route('userinfo.detail')}}"><button type="button" class="btn btn-success">详细信息</button></a>
  </div>
  <div class="btn-group" role="group">
    <a href="{{route('userinfo.teacher')}}"><button type="button" class="btn btn-default">教师附加信息</button></a>
  </div>
  <div class="btn-group" role="group">
    <a href="{{route('userinfo.author')}}"><button type="button" class="btn btn-default">作者附加信息</button></a>
  </div>
</div>
<br>


<!-- QQ号，地区，地址，工作单位 -->

<p><strong>详细资料</strong></p>

{!! Form::model($userinfo, ["route"=>"userinfo.detail.save", "method"=>"post"]) !!}

<small>
{{ Form::label("realname", "真实姓名") }}
{{ Form::text("realname", null, ["class"=>"form-control"])}}


{{ Form::label("qqnumber", "QQ号") }}
{{ Form::text("qqnumber", null, ["class"=>"form-control"])}}


{{ Form::label("workplace", "工作单位") }}
{{ Form::text("workplace", null, ["class"=>"form-control"])}}


{{ Form::label("province", "省份") }}
<select name="province" class="form-spacing-top">
    <option value="0">北京市</option>
</select><br>


{{ Form::label("city", "城市") }}
<select name="city" class="form-spacing-top">
    <option value="0">北京</option>
</select><br>

{{ Form::label("address", "地址", ["class"=>"form-spacing-top"]) }}
{{ Form::text("address", null, ["class"=>"form-control"])}}

<div class="col-xs-6 form-spacing-top">
{{ Form::submit("保存", ["class"=>"btn btn-primary btn-block"]) }}
</div>

<div class="col-xs-6 form-spacing-top">
<button class="btn btn-default btn-block">跳过</button>
</div>

</small>
{!! Form::close() !!}

@endsection