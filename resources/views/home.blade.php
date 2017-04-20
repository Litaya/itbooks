@extends('layouts.frame')

@section('title','首页')

@section('content')
    <form action="{{ route('book.index') }}" method="get" class="form-inline">
        <div class="form-group col-lg-10 col-xs-10" style="padding:0;">
            {{ Form::text('search', null, ['placeholder'=>'发现更多好书', "class"=>"form-control", "style"=>"margin: 0;"]) }}
        </div>
        <div class="form-group col-lg-2 col-xs-2" style="padding: 0;">
            <input type="submit" class="btn btn-default" value="搜索">
        </div>
    </form>
    <br>
    <div class="row" style="padding:0 20px 0 20px;">
        <h4>主编推荐</h4>
        <div class="col-xs-12" style="padding:0;">
            @if(count($topbooks)>0)
                <div class="col-xs-3" style="padding: 0 10px 0 0;">
                    <a href="{{ route("book.show",$topbooks[0]->id) }}"><img src="{{ $topbooks[0]->img_upload }}" alt="" style="width:100%; height:120px; box-shadow: 1px 1px 5px #666;"></a>
                </div>
                <div class="col-xs-9">
                    <p style="margin-bottom: 5px;"><a href="{{ route("book.show",$topbooks[0]->id) }}">{{ str_limit($topbooks[0]->name, $limit = 22, $end = '...') }}</a></p>
                    <small>作者: {{ str_limit($topbooks[0]->authors, $limit = 24, $end = "...") }}</small><br>
                    <small>出版时间: {{ date("Y-m-d",strtotime($topbooks[0]->publish_time)) }}</small><br>
                    <small>ISBN: {{ $topbooks[0]->isbn }}</small><br>
                    <small>价格: {{ $topbooks[0]->price }}</small><br>
                </div>
            @endif
        </div>
        <div class="col-xs-12" style="padding:0;margin-top: 20px;">
            @foreach($topbooks as $i=>$book)
                @if($i==0)
                    @continue
                @endif
                <div class="col-xs-3" style="padding:0 10px 15px 0;">
                    <a href="{{ route('book.show',$book->id) }}">
                        <img src="{{ $book->img_upload }}" alt="" style="width:100%; height:120px; box-shadow: 1px 1px 2px #ccc">
                        <small style="font-size:10px; color:#999">{{ str_limit($book->name, $limit = 10, $end = '...') }}</small>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row" style="padding:0 20px 0 20px;">
        <hr>
        <h4>热门图书</h4>
        <div class="col-xs-12" style="padding:0;">
            <div class="col-xs-3" style="padding: 0 10px 0 0;">
                <a href="{{ route("book.show",$hotbooks[0]->id) }}"><img src="{{ $hotbooks[0]->img_upload }}" alt="" style="width:100%; height:120px; box-shadow: 1px 1px 5px #666;"></a>
            </div>
            <div class="col-xs-9">
                <p style="margin-bottom: 5px;"><a href="{{ route("book.show",$hotbooks[0]->id) }}">{{ str_limit($hotbooks[0]->name, $limit = 22, $end = '...') }}</a></p>
                <small>作者: {{ str_limit($hotbooks[0]->authors, $limit = 24, $end = "...") }}</small><br>
                <small>出版时间: {{ date("Y-m-d",strtotime($hotbooks[0]->publish_time)) }}</small><br>
                <small>ISBN: {{ $hotbooks[0]->isbn }}</small><br>
                <small>价格: {{ $hotbooks[0]->price }}</small><br>
            </div>
        </div>
        <div class="col-xs-12" style="padding:0;margin-top: 20px;">
            @foreach($hotbooks as $i=>$book)
                @if($i==0 || $i>4) @continue @endif
                <div class="col-xs-3" style="padding:0 10px 15px 0;">
                    <a href="{{ route('book.show',$book->id) }}">
                        <img src="{{ $book->img_upload }}" alt="" style="width:100%; height:120px; box-shadow: 1px 1px 2px #ccc">
                        <small style="font-size:10px; color:#999">{{ str_limit($book->name, $limit = 10, $end = '...') }}</small>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <div class="row" style="padding:0 20px 0 20px;">
        <hr>
        <h4>最新上架</h4>
        <div class="col-xs-12" style="padding:0;">
            <div class="col-xs-3" style="padding: 0 10px 0 0;">
                <a href="{{ route("book.show",$newbooks[0]->id) }}"><img src="{{ $newbooks[0]->img_upload }}" alt="" style="width:100%; height:120px; box-shadow: 1px 1px 5px #666;"></a>
            </div>
            <div class="col-xs-9">
                <p style="margin-bottom: 5px;"><a href="{{ route("book.show",$newbooks[0]->id) }}">{{ str_limit($newbooks[0]->name, $limit = 22, $end = '...') }}</a></p>
                <small>作者: {{ str_limit($newbooks[0]->authors, $limit = 24, $end = "...") }}</small><br>
                <small>出版时间: {{ date("Y-m-d",strtotime($newbooks[0]->publish_time)) }}</small><br>
                <small>ISBN: {{ $newbooks[0]->isbn }}</small><br>
                <small>价格: {{ $newbooks[0]->price }}</small><br>
            </div>
        </div>
        <div class="col-xs-12" style="padding:0;margin-top: 20px;">
            @foreach($newbooks as $i=>$book)
                @if($i==0 || $i>4) @continue @endif
                <div class="col-xs-3" style="padding:0 10px 15px 0;">
                    <a href="{{ route('book.show',$book->id) }}">
                        <img src="{{ $book->img_upload }}" alt="" style="width:100%; height:120px; box-shadow: 1px 1px 2px #ccc">
                        <small style="font-size:10px; color:#999">{{ str_limit($book->name, $limit = 10, $end = '...') }}</small>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <div class="row" style="padding:0 20px 0 20px;">
        <hr>
        <h5><i class="fa fa-heart-o push"></i>猜你喜欢</h5>
        @foreach($booksrecommend as $i=>$book)
            <div class="col-xs-12" style="padding:0; margin-top: 20px">
                <div class="col-xs-3" style="padding:0;">
                    <a href="{{ route("book.show",$book->id) }}"><img src="{{ $book->img_upload }}" alt="" style="width:100%;height:120px;box-shadow: 1px 1px 5px #666;"></a>
                </div>
                <div class="col-xs-9">
                    <p style="margin-bottom: 5px;"><a href="{{ route("book.show",$book->id) }}">{{ str_limit($book->name, $limit = 22, $end = '...') }}</a></p>
                    <small>作者: {{ str_limit($book->authors, $limit = 24, $end = "...") }}</small><br>
                    <small>出版时间: {{ date("Y-m-d",strtotime($book->publish_time)) }}</small><br>
                    <small>ISBN: {{ $book->isbn }}</small><br>
                    <small>价格: {{ $book->price }}</small><br>
                </div>
            </div>
        @endforeach
    </div>
@endsection
