@extends('layouts.frame')

@section('title', "申请样书 | ".$book->name)


@section('content')
    <div class="container">
        <div class="row">
        <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">图书信息</div>
            <div class="panel-body">
                <ul>
                <li><strong>{{ $book->name }}</strong></li>
                <li>作者: {{ $book->authors }}</li>
                <li>类别: {{ "纪实".", "."科技" }}</li>
                <li>ISBN号: {{ $book->isbn }}</li>
                <li><p><small>图片展示</small></p>
                    @if($book->img_upload)
                    <p><img src="{{route('image', $book->img_upload)}}" style="width:80%" alt="图书附图"></img></p>
                    @else
                    <p><img src="{{URL::asset('test_images/404.jpg')}}" style="width:80%" alt="图书附图"></img></p>
                    @endif
                </li>
                </ul>
            </div>
        </div>
        </div>
        <div class="col-md-6 col-md-offset-2">
            {!! Form::open(["route"=>"bookreq.store", "method"=>"post"]) !!}
            {{ Form::hidden("user_id", Auth::id()) }}
            {{ Form::hidden("book_id", $book->id) }}
            {{ Form::label("address", "收货地址:", ["class"=>"form-spacing-top"]) }}
            {{ Form::text("address", null, ["class"=>"form-control"])}}            
            {{ Form::label("phone", "联系电话:", ["class"=>"form-spacing-top"]) }}
            {{ Form::text("phone", null, ["class"=>"form-control"])}}
            {{ Form::label("receiver", "收货人姓名:", ["class"=>"form-spacing-top"]) }}
            {{ Form::text("receiver", null, ["class"=>"form-control"])}}            
            {{ Form::label("message", "申请理由:", ["class"=>"form-spacing-top"]) }}
            {{ Form::textArea("message", null, ["class"=>"form-control"])}}
            {{ Form::submit("提交", ["class"=>"btn btn-primary btn-block form-spacing-top"])}}
            {!! Form::close() !!}
        </div>
    </div>

@endsection