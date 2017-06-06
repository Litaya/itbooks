@extends('layouts.frame')

@section('title','用户中心')

@section('content')
    <div class="container">
      <div class="col-xs-12" style="text-align: center">
          <p><img src="{{ Auth::user()->headimgurl }}" alt="" style="width:100px; height: 100px; border-radius: 50px"></p>
          <p> {{ Auth::user()->username }}</p>
      </div>
        <div class="col-xs-12" style="padding:0;">
            <hr>
            <div class="list-group">
                <a href="{{ route('purchase.index') }}" class="list-group-item">
                    充值
                </a>
                <a class="list-group-item" href="{{ route('favorite.manage') }}">个人收藏</a>
                <a class="list-group-item" href="{{ route('paidbook.index') }}">已购书籍</a>
                <a class="list-group-item" href="javascript:void(0)">暂未开放</a>
            </div>
        </div>
    </div>
@stop
