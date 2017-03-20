@extends('layouts.frame')

@section('title', '身份认证')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if(sizeof($success_records)>0)
                            您已认证成为:
                            @foreach($identities as $identity)
                                {{ $identity." " }}
                            @endforeach
                        @else
                            您还没有认证为任何身份
                        @endif
                        <br>
                        <small style="font-size: 12px; color:grey"> 您可 <a href="{{ route('cert.create') }}">点击此处</a> 进行@if(sizeof($success_records)>0)其他@endif身份认证 </small>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h4>申请记录</h4>
                <hr>
                @foreach($certifications as $certification)
                    <div class="panel panel-{{ $certification->status==0?'primary':($certification->status==1?"success":"danger") }}">
                        <div class="panel-heading">
                            {{ $certification->cert_name=="TEACHER"?"教师":($certification->cert_name=="AUTHOR"?"作者":$certification->cert_name) }}-{{ $certification->status==0?'正在审核':($certification->status==1?"通过":"未通过") }}
                            <small>{{ $certification->updated_at }}</small>
                        </div>
                        <div class="panel-body">
                            @if($certification->cert_name=="TEACHER")
                                <strong>真实姓名：</strong>{{ $certification->realname }} <br>
                                <strong>工作单位：</strong>{{ $certification->workplace }} <br>
                                <strong>申请时间：</strong>{{ $certification->created_at }}<br>
                            @endif
                            @if($certification->status == 2)
                                <strong style="color:orangered">拒绝理由：</strong>{{ $certification->message }}<br>
                            @endif
                            <img src="{{route('image', $certification->img_upload)}}" alt="" width="100%">
                            <p></p>
                                <a href="{{ route("cert.show",['cert'=>$certification->id]) }}" class="btn btn-primary"><i class="fa fa-info push"></i>详细信息</a>
                            @if($certification->status == 0)
                                <a class="btn btn-success" href="{{ route('cert.edit',['cert'=>$certification->id]) }}"><i class="fa fa-pencil-square-o push"></i>修改</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection