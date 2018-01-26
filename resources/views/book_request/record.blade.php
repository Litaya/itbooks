@extends('layouts.frame')

@section('title', '样书申请')

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
            {{--<p style="font-size: 12px; color:#ccc">Tips:&nbsp;您可在申请详情页上传相关书籍的学校订书单,审核通过后相关申请不扣总的申请次数</p>--}}
            <div class="panel panel-default">
                <div class="panel-body">
                    <small style="font-size: 12px; color:grey">亲爱的{{ Auth::user()->username }}，您好。您共申请了{{ sizeof(Auth::user()->bookRequests()->whereIn('status',[1,0])->get()) }}本样书，今年的总申请额度还有{{ json_decode(Auth::user()->json_content)->teacher->book_limit }}本。
                        <br>您可<a href="{{ route("bookreq.index") }}">点击此处</a>申请样书</small>
                </div>
            </div>
        </div>
        <div class="row">
            <h4>申请记录</h4>
            <div class="list-group">
                @if(sizeof($book_requests) == 0)
                    <p style="text-align: center; font-size: 14px;">暂无申请记录，快去<a href="{{ route('bookreq.index') }}">申请</a>吧！</p>
                @else
                    @foreach($book_requests as $bookreq)
                        <a href="{{ !empty($bookreq->book) ? route('bookreq.show', $bookreq->id) : '#' }}" class="list-group-item">
                            @if(!empty($bookreq->book))
                                <h5 class="list-group-item-heading">{{ $bookreq->book->name }} <small style="color:red">[点击查看详情]</small></h5>
                            @else
                                <h5 class="list-group-item-heading">[本社不再提供此书]</h5>
                            @endif
                            <small class="list-group-item-text">
                                {{ $bookreq->created_at }}&nbsp;
                                <span style="color:{{ $bookreq->status==0?"#7098DA":($bookreq->status==1?"green":"red") }}">
                                {{ $bookreq->status==0?"正在审核":($bookreq->status==1?"已通过":"未通过") }}
                            </span>
                            </small>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="row" id="pages" style="text-align: center">
            <div class="col-lg-12 col-md-12 col-xs-12"></div>
            {{ $book_requests->appends(Input::except('page'))->links('vendor.pagination.default')  }}
        </div>

        <!-- TODO 这里太暴力，写死了，要改 -->
        @if(!empty($banner_items))
            <div class="row">
                <hr>
                <div class="col-lg-12">
                    @foreach($banner_items as $material)
                        <a href="{{ $material->display==1?route("material.show",$material->id):$material->url }}">
                            {{--<a href="{{ $material->url }}">--}}
                            <div class="col-xs-12 item">
                                <div class="col-xs-3" style="padding: 0;">
                                    <img class="item-img" src="{{ $material->cover_path }}" alt="">
                                </div>
                                <div class="col-xs-9 item-content">
                                    <p class="item-title">{{ \Illuminate\Support\Str::limit($material->title,40) }}</p>
                                    <small class="item-hint" style="position: absolute; bottom: 2px;">阅读 {{ $material->reading_quantity  }}</small>
                                    <small class="item-hint" style="position: absolute; bottom: 2px; right: 5px;">{{ $material->wechat_update_time }}</small>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

@endsection
