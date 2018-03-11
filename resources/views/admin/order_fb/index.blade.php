@extends('admin.layouts.frame')

@section('title', '用户订购反馈管理')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>用户订购反馈记录</h4>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th width="8%">用户</th>
                                <th width="15%">书名</th>
                                <th width="8%">ISBN</th>
                                <th width="8%">所属分社</th>
                                <th width="5%">数量</th>
                                <th width="8%">订购日期</th>
                                <th width="13%">申请时间</th>
                                <th width="15%">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order_fbs as $fb)
                                <td>{{ $fb->user_realname }}</td>
                                <td>{{ $fb->book->name }}</td>
                                <td>{{ $fb->book_isbn }}</td>
                                <td>{{ $fb->bookDepartmentName() }}</td>
                                <td>{{ $fb->order_count }}</td>
                                <td>{{ date("Y-m-d",time($fb->order_time)) }}</td>
                                <td>{{ date("Y-m-d H:i",time($fb->created_at)) }}</td>
                                <td>
                                    <div class="row">
                                        <a href="{{ route('admin.order_fb.show',['id'=>$fb->id]) }}"><button class="btn btn-sm btn-primary">查看</button></a>
                                        @if($fb->status == 0)
                                            <button class="btn btn-sm btn-danger " data-toggle="modal"
                                                    data-target="#reject-modal-{{$fb->id}}">拒绝</button>
                                            <a href="{{ route('admin.order_fb.pass',['id'=>$fb->id]) }}"><button class="btn btn-sm btn-success">同意</button></a>
                                        @else
                                            <a href="{{ route('admin.order_fb.reset',['id'=>$fb->id,'page'=>Input::get('page')]) }}"><button class="btn btn-sm btn-danger">重置</button></a>
                                            <span style="color:{{ $fb->status == 1?"green":"red" }}; padding-left: 10px;">
                                                {{ $fb->status == 1?'已通过':($fb->status == -1?"用户取消":"已拒绝") }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                            </tbody>
                        </table>

                        <div>
                            {{ $order_fbs->appends(Input::except('page'))->links() }}
                        </div>

                        @foreach($order_fbs as $fb)
                            <div class="modal fade" id="reject-modal-{{$fb->id}}"
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
                                            {!! Form::open(["route"=>["admin.order_fb.reject", $fb->id], "method"=>"POST"]) !!}
                                            {{ Form::label("message", "拒绝理由:") }}
                                            {{ Form::textarea("message", null, ["class"=>"form-control", "rows"=>"5"]) }}
                                            <input type="hidden" name="page" value="{{Input::get('page')}}">
                                            {{ Form::submit('确认', ['class'=>'btn btn-danger btn-lg btn-block', 'style'=>"margin-top: 10px"]) }}
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
