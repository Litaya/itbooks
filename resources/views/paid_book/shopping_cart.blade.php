@extends('layouts.frame')

@section('title', '购物车')

@section('content')


    <div class="row" style="padding:0 20px 0 20px;">
    @if($cart->count()==0)
    <h4>购物车</h4>
    <p><h1>您的购物车没有商品</h1></p>
    <p></p>
    @else
    <div style="float:left;">
        <h4>购物车</h4>
    </div>
         <div style="text-align:center;float:right;">
             <button style="width:100%;" onclick="location.href='{{ route("purchase.index") }}'">去结算>></button>
         </div>
    @endif
        <div class="col-xs-12" style="padding:0;">
                @foreach($cart as $book)
                <hr>
                <div style="clear:both">
                <div class="col-xs-3" style="padding: 0 10px 0 0;">
                    <a href="{{ route("book.show",$book->id) }}"><img src="{{ $book->img_upload }}" alt="" style="width:100%; height:120px; box-shadow: 1px 1px 5px #666;"></a>
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
                    <button style="width:100%;" onclick="location.href='{{ route("purchase.drop_cart",$book->isbn) }}'">删除</button>
                </div>
                </div>
                @endforeach
                <hr>
        </div>
    </div>

@endsection
