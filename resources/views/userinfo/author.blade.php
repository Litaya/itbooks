@extends("layouts.frame")

@section("title", "作者信息")

@section("content")

@include("userinfo._sub_header")


<!-- 证明材料/照片，未来出书计划 -->

<p><strong>作者信息</strong></p>

{!! Form::model($userinfo, ["route"=>"userinfo.author.save", "method"=>"post", "files"=>true]) !!}

<input id="sendrequest" type="hidden" name="sendrequest" value="false">

<small>
<div class="form-inline"><div class="form-group">
{{ Form::label("realname", "真实姓名") }}
{{ Form::text("realname", null, ["class"=>"form-control"])}}
</div></div>

<div class="form-inline"><div class="form-group">
{{ Form::label("workplace", "工作单位") }}
{{ Form::text("workplace", null, ["class"=>"form-control"])}}
</div></div>

<div class="form-group">
{{ Form::label("book_plan", "新书计划") }}
{{ Form::textarea("book_plan", null, ["class"=>"form-control"])}}
</div>

<hr>

@if(!empty($userinfo->img_upload))
<img class="img-responsive" src="{{route('image', $userinfo->img_upload)}}" width="100" height="100"></img>
@endif

{{ Form::label("img_upload", "上传图片材料", ["class"=>"form-spacing-top"]) }}
<small>(请上传图书封面、样书照片等可供验证作者身份的图片)</small>
{{ Form::file("img_upload", ["class"=>"form-control"])}}

{{ Form::submit("保存", ["class"=>"btn btn-primary btn-block form-spacing-top"])}}

@if($userinfo->role == "author")
<button class="btn btn-primary btn-block form-spacing-top" onclick="save_and_apply()">保存并申请认证</button>
@endif

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