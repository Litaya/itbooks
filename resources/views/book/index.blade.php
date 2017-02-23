@extends('layouts.frame')

@section('title', '图书')

@section('content')
    <style>
        .well-showcase {
            height: 90%;
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="panel">
                <div class="panel-heading">
                <p>精品图书推荐</p>
                </div>

                <div class="panel-body">
                <div class="row">
                <!-- 如何使其在移动端可以显示图片(响应式) -->
                @foreach($books as $book)
                    <div class="col-md-3">
                    <div class="well well-showcase"> <!-- 如何让每个well的高度固定下来 -->
                    <button style="background: transparent; border: none; text-align: left; width:100%" onclick="javascript:window.location.href='{{route("book.show", $book->id)}}'">
                    <p><strong>{{$book->name}}</strong></p>
                    <ul>
                    <li>作者 {{strlen($book->authors)>20 ? (substr($book->authors,0,17)."..."):($book->authors)}}</li>
                    <li>定价 {{$book->price}}元</li>
                    <li><small>清华大学出版社</small></li>
                    </ul>
                    </button>
                    </div>
                    </div>
                @endforeach
                </div>
                </div>
        </div>
    </div>

@endsection