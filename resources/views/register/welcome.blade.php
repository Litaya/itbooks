@extends("layouts.frame")

@section("title", "注册")

@section("content")


@if($role == "teacher")
<p>您已经完成教师注册，管理员审批通过后可以享受所有教师服务。</p>
<p>您可以点击下面链接查看书圈的教师服务说明：
<a href="#">教师服务说明</a></p>
@elseif($role == "student")
<p>恭喜你注册成功！</p>
如果你是新手，可以点击下面连接，查看书圈的新手指南：
<a href="#">新手指南</a>
@elseif($role == "other")
<p>恭喜你注册成功！</p>
<p>如果你是新手，可以点击下面连接，查看书圈的新手指南：
<a href="#">新手指南</a></p>
@endif

@endsection