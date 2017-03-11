@extends('admin.layouts.frame')

@section('title', '用户认证 | 查看申请')

@section('content')

    <div class="container">

        <div class="row">
            <div class="panel">
                <div class="row">
                    <div class="col-md-6">
                        <p>真实姓名: {{$cert->realname}}</p>
                        <p>工作单位: {{$cert->workplace}}</p>
                        <p>申请类型: {{$cert->cert_name=="TEACHER"?"教师":"作者"}}</p>
                        @if($cert->cert_name == "TEACHER")
                        <hr>
                        <p>院系名称: {{ $cert->json_content["department"] }}</p>
                        <p>手机号: {{ $cert->json_content["phone"] }} </p>
                        <p>QQ号: {{ empty($cert->json_content["qqnumber"])?"未填":$cert->json_content["qqnumber"] }}</p>
                        <hr>
                        <p>教学情况</p>
                        <ol>
                            @for($i = 1; $i <= 3; $i++)
                            @if($cert->json_content["course_name_".$i])
                            <li>{{$cert->json_content["course_name_".$i]}}, 学生人数 {{$cert->json_content["number_stud_".$i]}}</li>
                            @endif
                            @endfor
                        </ol>
                        @endif
                        

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
                            <button type="button"
                                    class="btn btn-danger btn-block"
                                    data-toggle="modal"
                                    data-target="#register-modal">
                                    拒绝
                            </button>
                    </div>
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
            <h4 class="modal-title" id="register-modal-label">拒绝申请</h4>
            </div>
            <div class="modal-body">
            
            {!! Form::open(["route"=>["admin.cert.reject", $cert->id], "method"=>"POST"]) !!}
            {{ Form::label("message", "拒绝理由:") }}
            {{ Form::textarea("message", null, ["class"=>"form-control"]) }}
            {{ Form::submit('确认', ['class'=>'btn btn-danger btn-lg btn-block', 'style'=>"margin-top: 10px"]) }}
            {!! Form::close() !!}
            </div>
        </div>
     </div>
</div>

@endsection