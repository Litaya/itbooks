@extends('layouts.frame')

@section('title', '图书详情 | '.$book->name)

@section('content')
    <style>
        .well-showcase {
            height: 90%;
        }
    </style>
    <div class="container">
    <div class="row">

        <style>
        label {
            margin-top: 10px;
        }
        p {
            font-size: 10px;
        }
        .form-spacing-top{
            margin-top: 18px;
        }
        </style>
        <div class="panel panel-default">
        <div class="panel-heading">
        <div class="row">
            <div class="col-md-7">
            图书信息
            </div>
        </div>
        </div>

        <div class="panel-body">
        <div class="col-xs-5">
            @if($book->img_upload)
            <img class="img-responsive" alt="{{$book->name}}" src="{{URL::asset($book->img_upload)}}"></img>
            @else
            <img class="img-responsive" alt="{{$book->name}}" src="{{URL::asset('test_images/404.jpg')}}"></img>
            @endif

            @if(Auth::check())
            <div class="row">
            <div class="col-xs-12">
            <span class="pull-right">
                @if(!empty($userlike) and $userlike)
                <button id="like-button" class="btn btn-xs btn-default" style="color: #F77">
                <i id="like-icon" class="fa fa-heart" aria-hidden="true"></i>&nbsp;想读</a></button>
                @else
                <button id="like-button" class="btn btn-xs btn-default">
                <i id="like-icon" class="fa fa-heart-o" aria-hidden="true"></i>&nbsp;想读</a></button>
                @endif <!-- END LIKE IF -->

                @if(!empty($userread) and $userread)
                <button id="read-button" class="btn btn-xs btn-default" style="color: #F77" onclick="unread()">
                <i class="fa fa-history" aria-hidden="true"></i>&nbsp;读过</a></button>
                @else
                <button id="read-button" class="btn btn-xs btn-default" onclick="read()">
                <i class="fa fa-history" aria-hidden="true"></i>&nbsp;读过</a></button>
                @endif <!-- END READ IF -->
            </span>
            </div>
            </div>
            @endif

        </div>
        <div class="col-xs-7">
            <p><strong>{{$book->name}}</strong></p>
            <p>作者: {{$book->authors}}</p>
            <p>ISBN号: {{$book->isbn}}</p>
            <p>定价: {{$book->price}}</p>
            <p>类别: {{$book->type==0?"其他图书":($book->type==1?"教材":"非教材")}}</p>
            <p>出版时间: {{$book->publish_time}}</p>
            <hr>
            @if(Auth::check())
            <p>课件: 
                @if(!empty($book->kj_url))
                <a href="{{$book->kj_url}}">下载课件</a>
                @endif
                &nbsp;&nbsp;
                <a href="javascript:updateKjUrl();">扫描课件变更</a></p>
            @endif
            <!-- if the book is open to reservations, and the user has enough privilege -->
            <a href="{{route('bookreq.create', $book->id)}}"><button class="btn btn-primary btn-xs">申请样书</button></a>
            <!-- end if -->
            <a href="{{route('home')}}"><button class="btn btn-default btn-xs">返回首页</button></a>
        </div>
        
    </div>
</div>

<script>
function updateKjUrl(){
    var xmlhttp;
    if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
    else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
            location.reload();
    }
    xmlhttp.open("GET", "{{route('book.updatekj', $book->id)}}", true);
    xmlhttp.send();
}

$(document).ready(function(){
    if( {{ Auth::check() ? 1 : 0}} ){
        if( {{empty($userlike) ? 1 : 0}} )
            $('#like-button').attr("onclick", "like()");
        else
            $('#like-button').attr("onclick", "unlike()");
    }

});

function like(){ 
    response = $.ajax({
        url : '{{route('like', ['book_id'=>$book->id])}}',
        async : false,
        success : function(){
            $('#like-button').attr("onclick", "unlike()");
            $('#like-button').css({"color": "#F77"});
            $('#like-icon').attr("class", "fa fa-heart");
        },
    });
}

function unlike(){
    response = $.ajax({
        url : '{{route('unlike', ['book_id'=>$book->id])}}',
        async : false,
        success : function(){
            $('#like-button').attr("onclick", "like()");
            $('#like-button').css({"color": "#777"});
            $('#like-icon').attr("class", "fa fa-heart-o");
        }
    });
    
}

function read(){ 
    response = $.ajax({
        url : '{{route('read', ['book_id'=>$book->id])}}',
        async : false,
        success : function(){
            $('#read-button').attr("onclick", "unread()");
            $('#read-button').css({"color": "#F77"});
        },
    });
}

function unread() {
    response = $.ajax({
        url : '{{route('unread', ['book_id'=>$book->id])}}',
        async : false,
        success : function(){
            $('#read-button').attr("onclick", "read()");
            $('#read-button').css({"color": "#777"});
        },
    });
}
</script>


@endsection