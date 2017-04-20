@extends("layouts.frame")

@section("title", "注册")

@section("content")


{!! Form::open(["id"=>"file-form", "route"=>"register.teacher.save", "method"=>"post", "files"=>true]) !!}

<p>请上传可证明高校教师身份的照片，完成认证</p>
<ol>
<li>教师证</li>
<li>教师校园卡</li>
<li>清华出版社会议代表证</li>
<li>教务系统个人页面</li>
</ol>

{{ Form::label("img_upload", "文件上传") }}
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