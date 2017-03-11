@extends('layouts.frame')

@section('title', '会议详情 | '.$conference->name)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel">
                <h2>{{$conference->name}}</h2>
                <p class="text-center"><small>会议时间: {{date('Y-m-d H:i', strtotime($conference->time))}} <br> 主办方: {{$conference->host}}</small></p>
                <article>{{$conference->description}}</article>
                @if($register!==null)
                <p class="text-center"><small style="color: #F33">您已报名参加此会议</small></p>
                @endif
                <hr>
                <div class="row">
                    @if(Auth::check())
                        @if($register)
                            <div class="col-md-2 col-md-offset-4">
                            {!! Form::open(['route'=>['conference.cancel', $conference->id], 'method'=>'POST']) !!}
                            {{ Form::submit('取消报名', ['class'=>'btn btn-danger btn-md btn-block'])}}
                            {!! Form::close() !!}
                            </div>
                        @else
                            <div class="col-md-2 col-md-offset-4">
                            <button type="button"
                                    class="btn btn-primary btn-md btn-block"
                                    data-toggle="modal"
                                    data-target="#register-modal">
                                    我要报名
                            </button>
                            </div>
                        @endif
                    <div class="col-md-2">
                    <a href="{{route('conference.index')}}">
                    <button type="button"
                            class="btn btn-default btn-md btn-block">
                            返回列表
                    </button></a>
                    </div>
                    @else
                    <div class="col-md-2 col-md-offset-5">
                    <a href="{{route('conference.index')}}">
                    <button type="button"
                            class="btn btn-default btn-md btn-block"
                            onclick="window.history.back();">
                            返回列表
                    </button></a>
                    </div>
                    @endif

                </div>
            
                <!-- Register Form -->
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="register-modal"
     tabindex="-1" role="dialog"
     aria-labelledby="register-modal-label">
     <div class="modal-dialog" role="dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="register-modal-label">{{$conference->name}}</h4>
            </div>
            <div class="modal-body">
            <p><small>请填写您的基本信息以报名会议</small></p>
            {!! Form::open(['route'=>['conference.register', $conference->id], 'method'=>'POST']) !!}
            {{ Form::label('name', '姓名') }} {{ Form::text('name', null, ['class'=>'form-control']) }}
            {{ Form::label('school', '学校') }} {{ Form::text('school', null, ['class'=>'form-control']) }}
            {{ Form::label('position', '职务') }} {{ Form::text('position', null, ['class'=>'form-control']) }}
            {{ Form::label('job_title', '职称') }} {{ Form::text('job_title', null, ['class'=>'form-control']) }}
            {{ Form::label('phone', '手机号') }} {{ Form::text('phone', null, ['class'=>'form-control']) }}
            {{ Form::label('email', '邮箱') }} {{ Form::text('email', null, ['class'=>'form-control']) }}
            {{ Form::label('invoice_title', '发票抬头') }} {{ Form::text('invoice_title', null, ['class'=>'form-control']) }}
            {{ Form::label('mail_address', '邮寄地址') }} {{ Form::text('mail_address', null, ['class'=>'form-control']) }}
            {{ Form::submit('确认', ['class'=>'btn btn-primary btn-lg btn-block', 'style'=>"margin-top: 10px"]) }}
            {!! Form::close() !!}
            </div>
        </div>
     </div>
</div>
@endsection