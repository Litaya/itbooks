@extends('layouts.frame')

@section('title','书圈-文章')
@section('content')
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
        #cate-nav{
            min-height: 30px;
            margin: -22px -20px 22px -20px;
            background-color: #FAFAFA;
            box-shadow: 0 1px 5px #ccc;
        }
        .cate-nav-item{
            font-size: 12px;
            text-align: center;
            padding: 5px;
        }
        .cate-nav-item.active{
            color: #99ccff;
            border-bottom: 3px solid #99ccff;
        }
    </style>

    {{--<div class="row" id="cate-nav">--}}
        {{--<div class="col-xs-3 cate-nav-item active" style="border-left:none;">全部</div>--}}
        {{--@foreach($categories as $category)--}}
            {{--<div class="col-xs-3 cate-nav-item">{{ $category->name }}</div>--}}
        {{--@endforeach--}}
    {{--</div>--}}


    <form action="{{ route('material.index') }}" class="form" method="get">
        <input type="text" class="form-control" name="search" placeholder="您对什么感兴趣？">
    </form>

    <div class="row" style="min-height: 20px;"></div>
    @if(!empty($materials))
        @foreach($materials as $material)
            <a href="{{ $material->display==1?route("material.show",$material->id):$material->url }}">
                {{--<a href="{{ $material->url }}">--}}
                <div class="col-xs-12 item">
                    <div class="col-xs-3" style="padding: 0;">
                        <img class="item-img" src="{{ $material->cover_path }}" alt="">
                    </div>
                    <div class="col-xs-9 item-content">
                        <p class="item-title">{{ \Illuminate\Support\Str::limit($material->title,30) }}</p>
                        <small class="item-hint" style="position: absolute; bottom: 2px;">阅读 {{ $material->reading_quantity  }}</small>
                        <small class="item-hint" style="position: absolute; bottom: 2px; right: 5px;">{{ $material->wechat_update_time }}</small>
                    </div>
                </div>
            </a>
        @endforeach
        <div class="row" style="text-align:center">{{ $materials->appends(Input::except('page'))->links('vendor.pagination.simple-default')  }}</div>
    @endif
@stop
