@extends('admin.layouts.frame')

@section('title', '样书申请详情')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">申请情况</div>
                    <div class="panel-body">
                        <p><strong>申请书目:</strong> {{$bookreq->book->name}}</p>
                        <p><strong>ISBN:</strong> {{$bookreq->book->isbn}} </p>
                        <p><strong>申请用户:</strong> {{$bookreq->user->username}}</p>
                        <p><strong>单位:</strong> {{$bookreq->user->userinfo->workplace}}</p>
                        @if(in_array(PM::getAdminRole(), ["SUPERADMIN"]))
                            <p><strong>Email:</strong> {{$bookreq->user->email}}</p>
                        @endif
                        <p><strong>发起时间:</strong> {{$bookreq->created_at}}</p>
                        <p><strong>收件人:</strong>   {{$bookreq->receiver}}</p>
                        <p><strong>收件地址:</strong> {{$bookreq->address}}</p>
                        <p><strong>联系电话:</strong> {{$bookreq->phone}}</p>
                        <p><strong>审批状态:</strong>
                            <span style="color: {{$bookreq->status==0?'#777':($bookreq->status==1?'#4E4':'#E44')}}">
                                {{$bookreq->status==0?'审核中':($bookreq->status==1?'已通过':'未通过')}}
                                @if(in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"]))
                                    @if($bookreq->status != 0)
                                        <button class="btn-xs btn-danger" id="reset-button" data-toggle="modal" data-target="#reset-modal">重置</button>
                                    @endif
                                @endif
                            </span>
                        </p>
                        <p><strong>处理人:</strong>
                            @if(!empty($bookreq->handler))
                                {{$bookreq->handler->username}}
                            @else
                                <span style="color:#AAA;">[未记录]</span>
                            @endif
                        </p>
                        <p><strong>目前教材使用情况:</strong> {{empty(json_decode($bookreq->message)->book_plan)?"未填写":json_decode($bookreq->message)->book_plan}} </p>
                        <p><strong>留言:</strong> {{empty(json_decode($bookreq->message)->remarks)?"未填写":json_decode($bookreq->message)->remarks}} </p>
                        @if($bookreq->status==2)
                            <p style="color:red"><strong>拒绝理由:</strong>
                                @if(!empty(json_decode($bookreq->message)->admin_reply))
                                    {{json_decode($bookreq->message)->admin_reply}}
                                @endif
                            </p>
                        @endif
                    </div>
                </div>


                @if(in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"]))
                    @if($bookreq->status==1)
                        <div class="panel panel-default">
                            <div class="panel-heading">物流详情</div>
                            <div class="panel-body">
                                @if(empty($bookreq->order_number))
                                    <div class="col-md-4">
                                        <button type="button"
                                                class="btn btn-danger btn-block"
                                                data-toggle="modal"
                                                data-target="#shipping-modal">
                                            绑定订单号
                                        </button>
                                    </div>
                                @else
                                    <p><strong>订单号:</strong>{{$bookreq->order_number}} &nbsp; &nbsp;
                                        <a href="#"
                                           data-toggle="modal"
                                           data-target="#shipping-modal">
                                            修改
                                        </a>
                                    </p>
                                @endif
                            </div>
                        </div>
                @endif <!-- END STATUS=1 -->
            @endif <!-- END GET ROLE -->
            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">图书简介</div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <p><strong>{{$bookreq->book->name}}</strong></p>
                            <div class="col-md-6">
                                <img src="{{ url_file_exists("http://www.tup.com.cn/upload/bigbookimg/".$bookreq->book->product_number.".jpg")?"http://www.tup.com.cn/upload/bigbookimg/".$bookreq->book->product_number.".jpg":"/test_images/404.jpg" }}" class="img-responsive" alt="" style="width: 80%;">
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li>作者: {{$bookreq->book->authors}}</li>
                                    <li>ISBN号: {{$bookreq->book->isbn}}</li>
                                    <li>定价: {{$bookreq->book->price}}</li>
                                    <li>类别: {{$bookreq->book->type==0?"其他图书":($bookreq->book->type==1?"教辅":"非教辅")}}</li>
                                    <li>出版号: {{$bookreq->book->product_number}}</li>
                                    <li>出版时间: {{$bookreq->book->publish_time}}</li>
                                    <li>编辑: {{$bookreq->book->editor_name}}</li>
                                    <li>部门: {{$bookreq->book->department->code.'-'.$bookreq->book->department->name}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @if(in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"]))
                @if($bookreq->status==0)
                    <div class="col-md-2">
                        {!!Form::open(["route"=>["admin.bookreq.pass", $bookreq->id], "method"=>"POST"]) !!}
                        {{Form::submit("通过", ["class"=>"btn btn-success btn-block"])}}
                        {!!Form::close()!!}
                    </div>
                    <div class="col-md-2">
                        <button type="button"
                                class="btn btn-danger btn-block"
                                data-toggle="modal"
                                data-target="#reject-modal">
                            拒绝
                        </button>
                    </div>
                @endif
            @endif
            <div class="col-md-2">
                <a href="{{route('admin.bookreq.index')}}"><div class="btn btn-primary btn-block">返回列表</div></a>
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

                        {!! Form::open(["route"=>["admin.bookreq.reject", $bookreq->id], "method"=>"POST"]) !!}
                        {{ Form::label("message", "拒绝理由:") }}
                        {{ Form::textarea("message", null, ["class"=>"form-control"]) }}
                        {{ Form::submit('确认', ['class'=>'btn btn-danger btn-lg btn-block', 'style'=>"margin-top: 10px"]) }}
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="shipping-modal"
             tabindex="-1" role="dialog"
             aria-labelledby="shipping-modal-label">
            <div class="modal-dialog" role="dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="shipping-modal-label">绑定订单号</h4>
                    </div>
                    <div class="modal-body">

                        {!! Form::model($bookreq, ["route"=>["admin.bookreq.shipping", $bookreq->id], "method"=>"POST"]) !!}
                        {{ Form::label("order_number", "订单号:") }}
                        {{ Form::text("order_number", null, ["class"=>"form-control"]) }}
                        {{ Form::submit('确认', ['class'=>'btn btn-success btn-lg btn-block', 'style'=>"margin-top: 10px"]) }}
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="reset-modal"
             tabindex="-1" role="dialog"
             aria-labelledby="reset-modal-label">
            <div class="modal-dialog" role="dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="reset-modal-label">重置样书申请</h4>
                    </div>
                    <div class="modal-body">
                        {!! Form::model($bookreq, ["route"=>["admin.bookreq.reset", $bookreq->id], "method"=>"POST"]) !!}
                        {{ Form::submit('重置', ['class'=>'btn btn-danger btn-md btn-block', 'style'=>"margin-top: 10px"]) }}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    @endif <!-- END CHECK ROLE -->


@endsection