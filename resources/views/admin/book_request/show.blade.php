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
                        <p><strong>申请用户:</strong> {{$bookreq->user->username}}</p>
                        <p><strong>发起时间:</strong> {{$bookreq->created_at}}</p>
                        <p><strong>审批状态:</strong>
                            <span style="color: {{$bookreq->status==0?'#777':($bookreq->status==1?'#4E4':'#E44')}}">
                                {{$bookreq->status==0?'审核中':($bookreq->status==1?'已通过':'未通过')}}
                            </span>
                        </p>
                            @if($bookreq->status==2)
                            <p><strong>拒绝理由:</strong>
                                @if(!empty(json_decode($bookreq->message)->admin_reply))
                                {{json_decode($bookreq->message)->admin_reply}}
                                @endif
                            </p>
                            @endif
                        <p><strong>写书计划:</strong> {{empty(json_decode($bookreq->message)->book_plan)?"未填写":json_decode($bookreq->message)->book_plan}}</p>
                    </div>
                </div>


                @if($bookreq->status==1)
                    <div class="panel panel-default">
                        <div class="panel-heading">物流详情</div>
                        <div class="panel-body">
                            @if(empty($bookreq->order_number))
                            <div class="col-md-2">
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
                @endif

            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">图书简介</div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <p><strong>{{$bookreq->book->name}}</strong></p>
                            <div class="col-md-6">
                                @if($bookreq->book->img_upload)
                                    @if(strpos(strtolower($bookreq), "http")!==FALSE)
                                    <img src="{{$bookreq->book->img_upload}}" class="img-responsive" style="width: 80%"></img>
                                    @else
                                    <img src="{{route('image', $bookreq->book->img_upload)}}" class="img-responsive" style="width: 80%"></img>
                                    @endif
                                @else
                                    <img src="{{URL::asset('test_images/404.jpg')}}" class="img-responsive" style="width: 80%"></img>
                                @endif
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
            <div class="col-md-2">
                <a href="{{route('bookreq.record')}}"><div class="btn btn-primary btn-block">返回列表</div></a>
            </div>

        </div>
    </div>


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
                {{ Form::submit('确认', ['class'=>'btn btn-danger btn-lg btn-block', 'style'=>"margin-top: 10px"]) }}
                {!! Form::close() !!}
                
                </div>
            </div>
        </div>
    </div>

@endsection