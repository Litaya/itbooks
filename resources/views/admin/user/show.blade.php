@extends('admin.layouts.frame')

@section('title', '会议管理 | '.$user->username)

@section('content')
<div class="container">
    <div class="row">
    <div class="col-md-8">
        <p><strong>{{$user->username}}</strong></p>
        <ul>
            <li>邮箱: {{$user->email}} ({{$user->email_status==1?"已认证":"未认证"}})</li>
            <li>角色: 
            @if($user->userinfo->role == "teacher")
                教师
            @elseif($user->userinfo->role == "student")
                学生
            @elseif($user->userinfo->role == "staff")
                职员
            @else
                其他
            @endif
            </li>
            <li>真实姓名: {{$user->userinfo->realname}}</li>
            @if($user->userinfo->province)
            <li>所在地: {{$user->userinfo->province->name}} {{$user->userinfo->city->name}} </li>
            @endif
            @if($user->userinfo->address)
            <li>地址: {{$user->userinfo->address}} </li>
            @endif
        </ul>
    </div>

    <div class="col-md-3 col-md-offset-1">
        <div class="row">
        <div class="col-md-8">
        <p>创建时间: {{date('Y-m-d', strtotime($user->created_at))}}</p>
        <p>修改时间: {{date('Y-m-d', strtotime($user->updated_at))}}</p>
        </div>
        </div>
        <div class="row">
        <div class="col-md-6">
        <button class="btn btn-default btn-block" onclick="window.history.back();">返回</button>
        </div>
        </div>
        </div>
    </div>

    </div>
</div>

<script>

</script>
@endsection