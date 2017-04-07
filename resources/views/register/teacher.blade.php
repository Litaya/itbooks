@extends("layouts.frame")

@section("title", "注册")

@section("content")


{!! Form::open(["id"=>"file-form", "route"=>"register.teacher.save", "method"=>"post", "files"=>true]) !!}

{{ Form::label("img_upload", "文件上传") }}
<small>请上传教师证、校园卡等可以证明教师身份的图片材料</small>
{{ Form::file("img_upload", ["class"=>"form-control"]) }}

{!! Form::close() !!}

<button class="btn btn-primary" onclick="$('#file-form').submit();">提交</button>
<button class="btn btn-default" onclick="javascript:skip();">跳过</button>

<script>

function skip(){
    var sure = confirm("跳过此步将不会进行身份认证，您可以在个人资料页再次发起申请");
    if(sure){
        window.location.href = "{{route('register.welcome')}}";
    }
}

</script>

@endsection