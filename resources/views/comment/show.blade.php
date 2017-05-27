@extends('layouts.frame')

@section('title', '图书评价')

@section('content')
<div class="conmments" style="margin-top: 100px;">
      @foreach ($comments as $comment)
          <div class="one" style="border-top: solid 20px #efefef; padding: 5px 20px;">
              <div class="userid">
                      <h>{{ $comment->user_id }}</h>
                </div>
                <div class="content">
                    <p style="padding: 20px;">
                        {{ $comment->content }}
                    </p>
                </div>
          </div>
      @endforeach
</div>
@endsection
