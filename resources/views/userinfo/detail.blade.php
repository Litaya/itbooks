
@extends("layouts.frame")

@section("title", "详细资料")

@section("content")

@include("userinfo._sub_header")

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

<!--
{{ Form::label("province", "省份") }}
{{ Form::text("province", null, ["class"=>"form-control"])}}

{{ Form::label("city", "城市/县") }}
{{ Form::text("city", null, ["class"=>"form-control"])}}
-->

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