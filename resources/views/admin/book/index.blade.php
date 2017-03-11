@extends('admin.layouts.frame')
@section('title', '图书管理')
@section('content')

<style>


</style>


<div class="container">
    <div class="row">
    <div class="col-md-12">
        <div class="row">
        
            <!-- SEARCH BAR -->
            <div class="col-md-8"> 
            {!! Form::open(["route"=>"admin.book.index", "method"=>"GET"]) !!}
            {{ Form::text("search", null, ["placeholder"=>"ISBN、书名、作者..."]) }}
            {{ Form::submit("搜索") }}
            {!! Form::close() !!}
            </div>
            <!-- END SEARCH BAR -->

            <div class="col-md-2 pull-right">
            <a href="{{route('admin.book.create')}}"><button class="btn btn-default push-left">创建新书</button></a>
            <a href="{{route('admin.book.import')}}"><button class="btn btn-default push-left">批量导入</button></a>
            </div>
        </div>
        
        <div class="row">
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
            <td>{{$book->type==0?"其他":($book->type==1?"教材":"非教材")}}</td>
            <td>
                <a href="{{route('admin.book.show', $book->id)}}"><button class="btn btn-xs btn-primary">详情</button></a>
                <a href="{{route('admin.book.edit', $book->id)}}"><button class="btn btn-xs btn-primary">编辑</button></a>
            </td>
            </tr>
            @endforeach
        </tbody>
        </table>
        <div>
            {!! $books->appends(Input::except('page'))->links() !!}
        </div>
        </div>  
    </div>
    </div>
</div>

@endsection