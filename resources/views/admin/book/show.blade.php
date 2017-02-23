@extends('admin.layouts.frame')
@section('title', '图书管理')
@section('content')

<div class="container">
    <div class="row">
        <!--
        TODO:

        SHOW PAGE
        CREATE PAGE
        DESTROY PANEL
        
        -->
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
            <p>类别: {{$book->type==0?"其他图书":($book->type==1?"教辅":"非教辅")}}</p>
            <hr>
            <p>出版号: {{$book->product_number}}</p>
            <p>出版时间: {{$book->publish_time}}</p>
            <p>编辑: {{$book->editor_name}}</p>
            <hr> 
            <a href="{{route('admin.book.edit', $book->id)}}"><button class="btn btn-primary btn-md">修改</button></a>
            <button class="btn btn-default btn-md" onclick="parent.history.back();">返回</button>
        </div>
        
    </div>
</div>


@endsection