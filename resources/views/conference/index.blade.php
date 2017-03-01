@extends('layouts.frame')

@section('title', '最新会议')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-6">
<ul class="list-group">
    @foreach($conferences as $c)
    <li class="list-group-item">
        <a href="{{route('conference.show', $c->id)}}">{{$c->name}}</a>
    </li>
    @endforeach
</ul>
{{$conferences->links()}}
</div>
</div>
</div>
@endsection