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
    </style>
    <form action="{{ route('material.index') }}" class="form" method="get">
        <input type="text" class="form-control" name="search" placeholder="您对什么感兴趣？">
    </form>

    <div class="row" style="min-height: 20px;"></div>
    @if(!empty($materials))
        @foreach($materials as $material)
            <a href="{{ route("material.show",$material->id) }}">
            {{--<a href="{{ $material->url }}">--}}
                <div class="col-xs-12 item">
                    <div class="col-xs-3" style="padding: 0;">
                        <img class="item-img" src="{{ $material->cover_path }}" alt="">
                    </div>
                    <div class="col-xs-9 item-content">
                        <p class="item-title">{{ \Illuminate\Support\Str::limit($material->title,50) }}</p>
                        <small class="item-hint" style="position: absolute; bottom: 2px;">阅读 0 &nbsp;&nbsp;&nbsp;&nbsp; 评论 0</small>
                        <small class="item-hint" style="position: absolute; bottom: 2px; right: 5px;">{{ $material->wechat_update_time }}</small>
                    </div>
                </div>
            </a>
        @endforeach
        <div class="row" style="text-align:center">{{ $materials->links() }}</div>
    @endif
@stop
