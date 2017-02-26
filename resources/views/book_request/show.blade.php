@extends('layouts.frame')

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
                    </div>
                </div>

                
                @if($bookreq->status==1)
                <div class="panel panel-default">
                    <div class="panel-heading">物流详情</div>
                    <div class="panel-body">
                        当审批通过后，这里将会显示当前样书的物流状态。
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
                                <img src="{{route('image', $bookreq->book->img_upload)}}" class="img-responsive" style="width: 80%"></img>
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
                                <li>出版时间: {{date('Y-m-d', strtotime($bookreq->book->publish_time))}}</li>
                                <li>编辑: {{$bookreq->book->editor_name}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{route('bookreq.index')}}"><div class="btn btn-primary btn-block">返回列表</div></a>
    </div>

@endsection