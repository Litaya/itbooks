@extends('layouts.frame')

@section('title', '订单反馈')

@section('content')
    <div class="container">

        <style>
            .item{
                box-shadow: 1px 1px 1px #ccc;
                background-color: white;
                padding: 0;
                margin-bottom: 10px;
            }
            .item-img{
                width:100%;
                min-height:80px;
            }
            .item-content{
                min-height: 80px;
                padding: 0 5px 0 10px;
            }
            .item-title{
                margin: 0;
            }
            .item-hint{
                color: #cccccc;;
            }
        </style>

        <div class="row">
            <h4>订购反馈记录 <small><a href='{{ route('order_fb.index')}}'>点此申请订购反馈</a></small></h4><hr>
            <div class="list-group" style='margin-bottom:none'>
                @if(sizeof($order_fbs) == 0)
                    <p style="font-size: 14px;">暂无订购反馈记录，快去<a href="{{ route('order_fb.index') }}">填写申请反馈</a>吧！</p>
                    <p style="font-size: 14px;">提示：本页仅显示今年申请记录</p>
                @else
                    @foreach($order_fbs as $fb)
                        <a href="{{ route('order_fb.show', $fb->id) }}" class="list-group-item">
                            @if(!empty($fb->book))
                                <h5 class="list-group-item-heading">{{ $fb->book->name }} <small style="color:red">[点击查看详情]</small></h5>
                            @else
                                <h5 class="list-group-item-heading">[本社不再提供此书]</h5>
                            @endif

                            <small class="list-group-item-text">订购数量：{{ $fb->order_count }}&nbsp;</small><br>
                            <small class="list-group-item-text">订购日期：{{ $fb->order_time }}&nbsp;</small><br>
                            <small class="list-group-item-text">
                                反馈日期：{{ $fb->created_at }}&nbsp;
                            </small>
                                <br>
                            <small class="list-group-item-text">审核状态：
                            <span style="color:{{ $fb->status==0?"#7098DA":($fb->status==1?"green":"red") }}">
                                {{ $fb->status==0?"正在审核":($fb->status==1?"已通过":"未通过") }}
                            </span>
                            </small>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="row"><a href="{{ route('order_fb.index') }}"><button class="btn btn-primary">前往申请页</button></a></div>

        <div class="row" id="pages" style="text-align: center">
            <div class="col-lg-12 col-md-12 col-xs-12"></div>
            {{ $order_fbs->appends(Input::except('page'))->links('vendor.pagination.default')  }}
        </div>
    </div>
@endsection
