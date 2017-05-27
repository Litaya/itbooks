@extends('layouts.frame')

@section('title', '我的收藏')

@section('content')
<div class="favorites" style="margin-top: 90px;">
  @foreach ($user->favorites as $favorite)
    <div class="one" style="border-top: solid 20px #efefef; padding: 5px 20px;">
        <div class="userid">
                <h>{{ $user->id }}</h>
        </div>
        <div class="content">
              <p style="padding: 20px;">
                  {{ $favorite->target_id }}
              </p>
        </div>
    </div>
    @endforeach

</div>
@endsection
