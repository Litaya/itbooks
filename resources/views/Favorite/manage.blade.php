@extends('layouts.frame')

@section('title', '我的收藏')

@section('content')
  @if(Session::has('status'))
    <div class="alert alert-info">
     {{Session::get('status')}}
    </div>
  @endif
  @if(empty($favorite_books[0]))
    <div class="hint">
          <big>暂无收藏的图书</big>
    </div>
  @else
    @foreach($favorite_books as $favorite)
      <div class="panel-body" style="padding-left: 0px; padding-right: 0px;">
          <div class="col-xs-5">
              <a href="{{route('book.show', $favorite->id)}}"><img class="img-thumbnail img-in-well col-center-block" src="{{$favorite->img_upload}}" alt="Book"></a>
            </div>
            <div class="col-xs-7">
                <small>
                <a href="{{route('book.show', $favorite->id)}}"><p><strong>{{$favorite->name}}</strong></p></a>
                <small>作者: {{ str_limit($favorite->authors, $limit = 24, $end = "...") }}</small><br>
                <small>出版时间: {{ date("Y-m-d",strtotime($favorite->publish_time)) }}</small><br>
                <small>ISBN: {{ $favorite->isbn }}</small><br>
                <small>价格: {{ $favorite->price }}</small><br>
            </div>
            <form action="{{ route('favorite.drop',$favorite->id) }}" method="DELETE" style="display: inline;text-align:center;">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
            <button type="submit" class="btn btn-default btn-xs" >删除</button>
            </form>
      </div>
    @endforeach
  @endif
@endsection
