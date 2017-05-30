@extends('layouts.frame')

@section('title', '热门图书')

@section('content')
<div class="bookpopl" style="margin-top: 10px;">
  <div>
    <form action="{{ route('book_pop.show') }}" method="get" class="form-inline">
      <select name="dropdownOfPop">
        <option value="-1">请选择排序方式</option>
        <option value="0">按收藏排序</option>
        <option value="1">按销量排序</option>
      </select>
      <button type="submit" class="btn btn-primary btn-xs">确认</button>
    </form>
  </div>
  <div class="row" style="margin-top: 20px;">
      <div class="col-xs-12">
          <ul class="list-group">
              @foreach($books as $book)
              <div class="col-xs-9">
                  <p style="margin-bottom: 5px;"><a href="{{ route("book.show",$book->id) }}">{{ str_limit($book->name, $limit = 22, $end = '...') }}</a></p>
                  <small>作者: {{ str_limit($book->authors, $limit = 24, $end = "...") }}</small><br>
                  <small>出版时间: {{ date("Y-m-d",strtotime($book->publish_time)) }}</small><br>
                  @if($name==1)
                  <small>总销量：{{ $book->sales_volume }}</small><br>
                  @else
                  <small>总收藏：{{ $book->favorite_num }}</small><br>
                  @endif
                  <small>价格: {{ $book->price }}</small><br>
              </div>
              <div class="col-xs-3" style="padding: 0 10px 0 0;">
                  <a href="{{ route("book.show",$book->id) }}"><img class="img-responsive" alt="" src="{{URL::asset($book->img_upload)}}"></img></a>
              </div>
              @endforeach
          </ul>
      </div>
  </div>
  <div>
    <p>分享本页到:<a href="weibo.com"><img style="width:35px;height:30px;" src="test_images/weibo.jpg"></a><a href="qzone.qq.com"><img style="width:30px;height:30px;" src="test_images/qzone.jpg"></a></p>
  </div>
</div>
@endsection
