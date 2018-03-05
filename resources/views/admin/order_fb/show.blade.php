@extends('admin.layouts.frame')

@section('title', '用户订购反馈详情')

@section('content')
    <div class="container">
        <style>
            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
                border:none;
            }
        </style>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>订购反馈详情</h5>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <tr><td width="20%"><strong>书名</strong></td>
                                <td>{{ $order_fb->book->name }}</td></tr>
                            <tr><td><strong>ISBN</strong></td>
                                <td>{{ $order_fb->book->isbn }}</td></tr>
                            <tr><td><strong>申请用户</strong></td>
                                <td>{{ $order_fb->user->userInfo->realname }}</td></tr>
                            <tr><td><strong>工作单位</strong></td>
                                <td>{{ $order_fb->user->userInfo->workplace }}</td></tr>
                            <tr><td><strong>订购数量</strong></td>
                                <td>{{ $order_fb->order_count }}</td></tr>
                            <tr><td><strong>订购时间</strong></td>
                                <td>{{ $order_fb->order_time }}</td></tr>
                            <tr><td><strong>审批状态</strong></td>
                                <td style="color:{{ $order_fb->status==0?'dodgerblue':($order_fb->status==1?'forestgreen':'red') }}">
                                    {{ $order_fb->status==0?'审批中':($order_fb->status == 1?'已通过':'已拒绝') }}</td></tr>
                            <tr><td><strong>拒绝理由</strong></td>
                                <td>{{ empty($order_fb->refuse_message)?'[未填写]':$order_fb->refuse_message }}</td></tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-xs-4"><a href="{{ route('admin.order_fb.index') }}"><button class="btn btn-primary btn-block">返回</button></a></div>
                    <div class="col-lg-4 col-md-4 col-xs-4" data-toggle="modal" data-target="#reject-modal"><button class="btn btn-danger btn-block">拒绝</button></div>
                    <div class="col-lg-4 col-md-4 col-xs-4">
                        {!! Form::open(["route"=>["admin.order_fb.pass", $order_fb->id], "method"=>"POST"]) !!}
                        <input class="btn btn-success btn-block" type="submit" value="通过"/>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>订购证明</h5>
                    </div>
                    <div class="panel-body">
                        <img class="img-responsive" src="{{route('image', $order_fb->image_path)}}" width="100%"/>
                    </div>
                </div>
            </div>
        </div>
        @if(in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"]))
            <div class="modal fade" id="reject-modal"
                 tabindex="-1" role="dialog"
                 aria-labelledby="reject-modal-label">
                <div class="modal-dialog" role="dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="reject-modal-label">拒绝申请</h4>
                        </div>
                        <div class="modal-body">

                            {!! Form::open(["route"=>["admin.order_fb.reject", $order_fb->id], "method"=>"POST"]) !!}
                            {{ Form::label("message", "拒绝理由:") }}
                            {{ Form::textarea("message", null, ["class"=>"form-control"]) }}
                            {{ Form::submit('确认', ['class'=>'btn btn-danger btn-lg btn-block', 'style'=>"margin-top: 10px"]) }}
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection