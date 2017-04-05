@extends('layouts.frame_set')

@section('title','书圈-文章')
@section('content')
    <div class="row" id="content"></div>
    {{ $content }}
    <script>
        $("#content").html(<?php echo $content?>)
    </script>
@stop