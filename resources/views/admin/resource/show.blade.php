@extends('admin.layouts.frame')

@section('title', '上传资源')

@section('content')


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
                            <h4>上传资源</h4>
                        </div>
                        <div class="col-md-1 col-md-offset-4">
                            <button class="btn btn-sm btn-primary btn-block" onclick="window.history.back()">返回</button></a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <p><strong>资源标题：</strong>{{$resource->title}}</p>
                    <p><strong>资源描述：</strong>{{$resource->description}}</p>
                    <p>
                        <strong>对应书籍：</strong>
                        @if($resource->owner_book_id == 1)
                            @foreach($resource->books() as $book)
                                <a href="{{ route('admin.book.show',["id"=>$book->id]) }}">{{ $book->name }}</a>&nbsp;&nbsp;
                            @endforeach
                        @else
                            @if($resource->owner_book_id == 0)
                                全部
                            @else
                                无对应书籍
                            @endif
                        @endif
                    </p>
                    <p><strong>资源权限：</strong>
                        {{$resource->access_role}}
                    </p>
                    <p><strong>资源地址：</strong>
                        <a href="{{ $resource->file_upload }}">{{$resource->file_upload}}</a>
                    </p>
                    <hr>
                    <p><small>下载需要积分: {{$resource->credit}}</small></p>
                    <div class="col-md-3">
                        <a href="{{$resource->file_upload}}"><button class="btn btn-success btn-block form-spacing-top"]>下载资源</button></a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route("admin.resource.edit", $resource->id) }}"><button class="btn btn-success btn-block form-spacing-top"]>重新编辑</button></a>
                    </div>
                    <div class="col-md-3">
                        {!! Form::open(["route"=>["admin.resource.destroy", $resource->id], "method"=>"delete"]) !!}
                        {{ Form::submit("删除资源", ["class"=>"btn btn-danger btn-block form-spacing-top"]) }}
                        {!! Form::close() !!}
                    </div>
                    <div class="col-md-3">
                        <a href="{{route('admin.resource.index')}}">
                            <button class="btn btn-default btn-block form-spacing-top">返回列表</button>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
