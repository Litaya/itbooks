@extends('layouts.frame_norefer')

@section('title','书圈-文章')
@section('content')
    <div class="row" style="background-color: white;">
        <div class="col-xs-12">
            <h3>{{ $material->title }}</h3>
            <p style="margin:0 ">{{ date("Y-m-d",strtotime($material->wechat_update_time)) }} &nbsp;&nbsp; {{ $material->author }}</p>
            <hr style="margin-top:0 ">
        </div>
        <div class="col-xs-12" id="body" ></div>
        <hr style="margin-bottom: 0">
        <div class="col-xs-12">
            <p>
                @if(!empty($material->content_source_url))
                    <a href="{{ $material->content_source_url }}">阅读原文</a>&nbsp;&nbsp;
                @endif
                {{-- 阅读: {{ $material->reading_quantity }}&nbsp;&nbsp;评论: 0 --}}
            </p>
        </div>
    </div>
    <script>
        $("#body").html('<?php echo $material->content?>');
        $("[data-src]").each(function () {
            $(this).attr("src",$(this).attr('data-src'));
        });
        $("iframe").each(function () {
            $(this).attr("width",'100%');
            $(this).attr("height",'300px;');
        });
        $("img[data-src]").each(function () {
            if($(this).attr("data-s")==undefined){
                $(this).attr("width",'100%');
            }else{
                var max_width = parseInt($(this).attr("data-s").split(",")[0]);
                if($(this).attr("data-w")>max_width){
                    $(this).attr("width",'100%');
                }
            }
        });
    </script>
@stop
