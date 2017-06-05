@extends('layouts.frame')

@section('title', '相关评论')

@section('content')
<div class="conmments" style="margin-top: 10px;">
  @if(Session::has('status'))
    <div class="alert alert-info">
     {{Session::get('status')}}
    </div>
  @endif
  @if (empty($comments[0]))
    <div class="hint">
          <big>暂无评论</big>
    </div>
  @else
    @foreach ($comments as $comment)
      <div class="one" style="border: solid 2px #efefef; padding: 5px 20px;">
        <div class="username">
          {{$comment->user->username}}
          <img src="{{ isset($comment->user->headimgurl)?$comment->user->headimgurl:'/img/avatar.png' }}" alt="" style="margin: 0; padding: 0; width: 45px; height: 45px; border-radius: 25px" />
        </div>
        <div class="content" contenteditable="true" style="outline:0; resize:none;padding: 5px 20px;margin: 0;">
          {{ $comment->content }}</br>
          <p></p>
        </div>
        <div class="comment_date">
          <p>评论于{{$comment->created_at}}</p>
        </div>
      </div>
    @endforeach
  @endif
</div>
<div class="row" style="text-align:center">
  {{ $comments->links() }}
</div>

@endsection
