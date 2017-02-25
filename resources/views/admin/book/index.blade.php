@extends('admin.layouts.frame')
@section('title', '图书管理')
@section('content')

<div class="container">
    <div class="row">
    <div class="col-md-12">
        <table class="table" style="column-width: 10px">
        <thead>
            <th>ID</th>
            <th>书名</th>
            <th>作者</th>
            <th>ISBN</th>
            <th>分类</th>
            <th></th>
        </thead>
        <tbody>
            @foreach($books as $book)
            <tr>
            <td>{{$book->id}}</td>
            <td width="30%"><a href="{{route("admin.book.show", $book->id)}}">{{$book->name}}</a></td>
            <td width="30%">{{$book->authors}}</td>
            <td>{{$book->isbn}}</td>
            <td>{{$book->type==0?"未知":($book->type==1?"教辅":"非教辅")}}</td>
            <td>
                <a href="{{route('admin.book.show', $book->id)}}"><button class="btn btn-xs btn-primary">详情</button></a>
                <a href="{{route('admin.book.edit', $book->id)}}"><button class="btn btn-xs btn-primary">编辑</button></a>
            </td>
            </tr>
            @endforeach
        </tbody>
        </table>
        <div>
            {{$books->links()}} <!-- pagination bar -->
        </div>
    </div>
    </div>
</div>

@endsection