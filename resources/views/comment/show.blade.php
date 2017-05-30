@extends('layouts.frame')

@section('title', '相关评论')

@section('content')
<div class="conmments" style="margin-top: 10px;">
      @foreach ($comments as $comment)
          <div class="one" style="border: solid 2px #efefef; padding: 5px 20px;">
              <div class="username">
                {{$comment->user->username}}
                <img src="{{ isset($comment->user->headimgurl)?$comment->user->headimgurl:'/img/avatar.png' }}" alt="" style="margin: 0; padding: 0; width: 45px; height: 45px; border-radius: 25px" />
              </div>

              <div class="content" style="">
                <p style="padding: 20px;">
                  {{ $comment->content }}
                </p>
                <p>
                  评论于{{$comment->created_at}}
                </p>
              </div>
          </div>
      @endforeach
</div>
@endsection
