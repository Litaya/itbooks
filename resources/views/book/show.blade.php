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
        <div class="col-md-4">
            @if($book->img_upload)
            <img class="img-responsive" alt="{{$book->name}}" src="{{URL::asset($book->img_upload)}}"></img>
            @else
            <img class="img-responsive" alt="{{$book->name}}" src="{{URL::asset('test_images/404.jpg')}}"></img>
            @endif
        </div>
        <div class="col-md-8">
            <p><strong style="font-size: 125%">{{$book->name}}</strong></p>
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
            <a href="{{route('bookreq.create', $book->id)}}"><button class="btn btn-primary btn-md">申请样书</button></a>
            <!-- end if -->
            <a href="{{route('book.index')}}"><button class="btn btn-default btn-md">返回首页</button></a>
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
</script>


@endsection