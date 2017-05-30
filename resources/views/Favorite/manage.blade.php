@extends('layouts.frame')

@section('title', '我的收藏')

@section('content')

  @foreach($favorite_books as $favorite)
    <div class="panel-body" style="padding-left: 0px; padding-right: 0px;">
        <div class="col-xs-5">
            <a href="{{route('book.show', $favorite->id)}}"><img class="img-thumbnail img-in-well col-center-block" src="{{$favorite->img_upload}}" alt="Book"></a>
          </div>
          <div class="col-xs-7">
              <small>
              <a href="{{route('book.show', $favorite->id)}}"><p><strong>{{$favorite->name}}</strong></p></a>
              作者: {{$favorite->authors}}<br>
              定价: {{$favorite->price}}
              <p>ISBN号：{{$favorite->isbn}}</p>
              <p>出版时间：{{$favorite->publish_time}}</p>

          </div>
          <form action="{{ route('favorite.drop',$favorite->id) }}" method="DELETE" style="display: inline;">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
          <button type="submit" >删除</button>
          </form>
    </div>
  @endforeach

@endsection
