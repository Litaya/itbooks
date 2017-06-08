@extends('layouts.frame')

@section('title', '已购图书')

@section('content')


    <div class="row" style="padding:0 20px 0 20px;">
    <h4>已购图书</h4>
        <div class="col-xs-12" style="padding:0;">
                @foreach($paidbook as $book)
                <div style="clear:both">
                <div class="col-xs-3" style="padding: 0 10px 0 0;">
                    <a href="{{ route("book.show",$book->id) }}">
                    @if($book->img_upload)
                    <img class="img-responsive" alt="{{$book->name}}" src="{{URL::asset($book->img_upload)}}"></img>
                    @else
                    <img class="img-responsive" alt="{{$book->name}}" src="{{URL::asset('test_images/404.jpg')}}"></img>
                    @endif
                    </a>
                </div>
                <div class="col-xs-9">
                    <p style="margin-bottom: 5px;"></p>
                    <small>{{ $book->name }}</small><br>
                    <small>作者: {{ str_limit($book->authors, $limit = 24, $end = "...") }}</small><br>
                    <small>出版时间: {{ date("Y-m-d",strtotime($book->publish_time)) }}</small><br>
                    <small>ISBN: {{ $book->isbn }}</small><br>
                    <small>价格: {{ $book->price }}</small><br>
                    <small>购买时间: {{ date("Y-m-d",strtotime($book->publish_time)) }}</small><br>
                </div>
                <div style="text-align:center;">
                    <button style="width:100%;" onclick="location.href='{{ route("comment.create",$book->id) }}'">评论</button>
                </div>
                <hr>
                </div>
                @endforeach
        </div>
    </div>

@endsection
