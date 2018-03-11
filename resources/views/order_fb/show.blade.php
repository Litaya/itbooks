@extends('layouts.frame')

@section('title', '订单反馈')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-xs-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        订购反馈状态
                    </div>
                    <div class="panel-body">
                        <span><strong>申请书目：</strong>{{ $fb->book->name }}</span><br>
                        <span><strong>订购数量：</strong>{{ $fb->order_count }}</span><br>
                        <span><strong>订购时间：</strong>{{ $fb->order_time }}</span><br>
                        <span><strong>发起时间：</strong>{{ $fb->created_at }}</span><br>
                        <span><strong>当前状态：</strong><span style="color: {{ $fb->status==0?'#7098DA':($fb->status==1?'#4E4':'#E44')}}">{{$fb->status==0?'审核中':($fb->status==1?'已通过':'未通过')}}</span>
                        </span><br>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-xs-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        书籍详情
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-xs-4">
                                <img src="{{ url_file_exists("http://www.tup.com.cn/upload/bigbookimg/".$fb->book->product_number.".jpg")?"http://www.tup.com.cn/upload/bigbookimg/".$fb->book->product_number.".jpg":"/test_images/404.jpg" }}" class="img-responsive" alt="" style="width: 100%;">
                            </div>
                            <div class="col-lg-8 col-md-8 col-xs-8">
                                <small><strong>作者：</strong>{{ $fb->book->authors }}</small><br>
                                <small><strong>ISBN：</strong>{{ $fb->book->isbn }}</small><br>
                                <small><strong>定价：</strong>{{ $fb->book->price }}</small><br>
                                <small><strong>类别：</strong>{{ $fb->book->type==0?"其他图书":($fb->book->type==1?"教材":"非教材") }}</small><br>
                                <small><strong>出版时间：</strong> {{date('Y-m-d', strtotime($fb->book->publish_time))}}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-xs-12 col-md-12">
                <a class="btn btn-primary" href="{{ route('order_fb.records') }}">返回</a>
            </div>
        </div>
    </div>
@endsection