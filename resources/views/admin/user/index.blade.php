@extends('admin.layouts.frame')

@section('title', '用户管理')

@section('content')

<div class="row">
<div class="col-xs-8 col-md-6">
{!! Form::open(["route"=>"admin.user.index", "method"=>"GET"]) !!}
{{ Form::text('search', null, ['placeholder'=>'用户名、邮箱、真实姓名']) }}
{{ Form::select('role', ["all"=>"全部", "teacher"=>"教师", "staff"=>"职员", "student"=>"学生", "other"=>"其他"], Input::get('role')) }}
{{ Form::submit('搜索') }}
{!! Form::close() !!}
</div>

<div class="col-xs-4 col-md-6">
<a href="{{route('admin.user.exportteacher')}}"><button class="btn btn-md btn-success pull-right">导出教师信息</button></a>
</div>
</div>


<div class="row">
<div class="col-xs-12 col-md-12">
<table class="table">
<thead>
<tr>
    <th>用户名</th>
    <th>邮箱</th>
    <th>角色</th>
    <th>真实姓名</th>
    <th>操作</th>
</tr>

</thead>
<tbody>

@foreach($users as $user)
<tr>
    <td>{{$user->username}}</td>
    <td>{{$user->email}}</td>
    <td>
    @if(!empty($user->userinfo))
        @if($user->userinfo->role == "teacher")
        教师
        @elseif($user->userinfo->role == "student")
        学生
        @elseif($user->userinfo->role == "staff")
        职员
        @elseif($user->userinfo->role == "other")
        其他
        @endif
    @endif
    </td>
    <td>
    @if(!empty($user->userinfo) && $user->userinfo->realname)
    {{$user->userinfo->realname}}
    @else
    未填写
    @endif
    </td>
    <td>
    <a href="{{route('admin.user.show', $user->id)}}"><button class="btn-xs btn-default">详细信息</button></a>
    @if(strtoupper(PM::getAdminRole()) == "SUPERADMIN")
        <button class="btn-xs btn-default" onclick="javascript:confirmAndPromote({{$user->id}});">提升为管理员</button>
        @if($user->certificate_as != "" and $user->certificate_as != "NOBODY")
            <button class="btn-xs btn-default">取消认证</button>
        @endif
    @endif
    <!--<button class="btn-xs btn-default">删除账号</button>-->
    </td>

</tr>
@endforeach


</tbody>
</table>

{{ $users->appends(Input::except('page'))->links() }}

</div>
</div>

<form id="promote-form" action="{{route('admin.user.promote')}}" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="id" id="promote-id" value="">
</form>


<script>
function confirmAndPromote(id){
    var theForm = document.getElementById("promote-form");
    var theId = document.getElementById("promote-id");
    theId.value = id;
    var sure = confirm("确定要将此用户提升为管理员吗？");
    if(sure){
        theForm.submit();
    }
}
</script>

@endsection