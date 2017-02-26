@extends('admin.layouts.frame')

@section('title', '用户认证 | 查看申请')

@section('content')

    <div class="container">

        <div class="row">
            <div class="panel">
                <div class="row">
                    <div class="col-md-6">
                        <p>真实姓名: {{$cert->realname}}</p>
                        <p>证件号: {{$cert->id_number}}</p>
                        <p>申请类型: {{$cert->cert_name=="TEACHER"?"教师":"作者"}}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>上传证件</strong></p>
                        <p><img src="{{route('image', $cert->img_upload)}}" class="img-responsive" style="width: 75%"></img></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-5">
                        {!! Form::open(["route"=>["admin.cert.pass", $cert->id], "method"=>"POST"]) !!}
                        {{ Form::submit("通过", ["class"=>"btn btn-success btn-block"]) }}
                        {!! Form::close() !!}
                    </div>
                    <div class="col-md-5">
                        {!! Form::open(["route"=>["admin.cert.reject", $cert->id], "method"=>"POST"]) !!}
                        {{ Form::submit("拒绝", ["class"=>"btn btn-danger btn-block"]) }}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection