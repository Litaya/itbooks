@extends('layouts.frame')

@section('title', '热门图书')

@section('content')
<div class="bookpopl" style="margin-top: 10px;">
  
  <div class="row" style="margin-top: 20px;">
      <div class="col-xs-12">
          <ul class="list-group">
              @foreach($books as $book)
              <div class="col-xs-9">
                  <p style="margin-bottom: 5px;"><a href="{{ route("book.show",$book->id) }}">{{ str_limit($book->name, $limit = 22, $end = '...') }}</a></p>
                  <small>作者: {{ str_limit($book->authors, $limit = 24, $end = "...") }}</small><br>
                  <small>出版时间: {{ date("Y-m-d",strtotime($book->publish_time)) }}</small><br>
                  <small>ISBN: {{ $book->isbn }}</small><br>
                  <small>价格: {{ $book->price }}</small><br>
              </div>
              <div class="col-xs-3" style="padding: 0 10px 0 0;">
                  <a href="{{ route("book.show",$book->id) }}"><img src="{{ $book->img_upload }}" alt="" style="width:100%; height:120px; box-shadow: 1px 1px 5px #666;"></a>
              </div>
              @endforeach
          </ul>
      </div>
  </div>
</div>
@endsection
