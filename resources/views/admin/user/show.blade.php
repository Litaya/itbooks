@extends('admin.layouts.frame')

@section('title', '用户管理 | '.$user->username)

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
            <li>所在地: {{$user->userinfo->province->name}} {{empty($user->userinfo->city->name)?"":$user->userinfo->city->name}} </li>
            @endif
            @if($user->userinfo->address)
            <li>地址: {{$user->userinfo->address}} </li>
            @endif

            @if(!empty($j = json_decode($user->userinfo->json_content, true)))
            @foreach($j as $k=>$v)
                @if(!empty($v))
                    @if($k == "position")
                    <li>职称: {{$v}}</li>
                    @elseif($k == "course_name_1")
                    <li>教授课程1: {{$v}}</li>
                    <li>学生人数1: {{$j["number_stud_1"]}}</li>
                    @elseif($k == "course_name_2")
                    <li>教授课程2: {{$v}}</li>
                    <li>学生人数2: {{$j["number_stud_2"]}}</li>
                    @elseif($k == "course_name_3")
                    <li>教授课程3: {{$v}}</li>
                    <li>学生人数3: {{$j["number_stud_3"]}}</li>
                    @elseif($k == "book_plan")
                    <li>著书计划: {{$v}}</li>
                    @elseif($k == "department")
                    <li>院系: {{$v}}</li>
                    @elseif($k == "jobtitle")
                    <li>职位: {{$v}}</li>
                    @elseif($k == "qqnumber")
                    <li>QQ号: {{$v}}</li>
                    @endif
                @endif
            @endforeach
            @endif

        </ul>
    </div>

    <div class="col-md-3 col-md-offset-1">
        <div class="row">
        <div class="col-md-8">
        <p>创建时间: {{date('Y-m-d', strtotime($user->created_at))}}</p>
        <p>修改时间: {{date('Y-m-d', strtotime($user->updated_at))}}</p>
        <button type="button"
                class="btn btn-danger btn-md btn-block"
                data-toggle="modal"
                data-target="#change-userinfo-modal">
                强制修改
        </button>
        <br>
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


<div class="modal fade" id="change-userinfo-modal"
    tabindex="-1" role="dialog"
    aria-labelledby="order-modal-label">
    <div class="modal-dialog" role="dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="change-userinfo-modal">修改用户角色</h4>
            </div>
            <div class="modal-body">
            {!! Form::open(["route"=>["admin.user.update", $user->id], "method"=>"POST"]) !!}
            {{ Form::label("role", "用户角色") }}
            {{ Form::select('role', ['TEACHER' => '教师', 'STUDENT' => '学生', "OTHER" => "其他", "STAFF" => "职员"], $user->certificate_as, ['placeholder' => '选择用户角色']) }}
            @if($user->certificate_as == "TEACHER")
            <br>
            {{ Form::label("book_limit", "教师样书申请限额") }}
            {{ Form::number('book_limit', json_decode($user->json_content)->teacher->book_limit) }}
            @endif
            {{ Form::submit('确认', ['class'=>'btn btn-success btn-lg btn-block', 'style'=>"margin-top: 10px"]) }}
            {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>


<script>

</script>
@endsection