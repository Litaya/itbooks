@extends('layouts.frame')

@section('title', '图书')

@section('content')
    <style>
        .well-showcase {
            height: 220px;
            padding: 0;
            margin: 0;
        }

        .col-center-block {
            float: none;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .img-in-well {
            width: auto;
            max-width: 100%;
            height: 65%;
        }

    </style>
    <div class="container">
        <div class="container-fluid marginless">
            <div class="row">
                <form action="{{ route('book.index') }}" method="get" class="form-inline">
                    <div class="col-xs-10 col-md-10 col-xs-10 form-group" style="padding: 0; margin: 0;">
                        {{ Form::text('search', null, ['placeholder'=>'发现更多好书', "class"=>"form-control"]) }}
                    </div>
                    <div class="col-xs-2 col-md-2 form-group" style="padding: 0; margin: 0;">
                        <button type="submit" class="btn btn-default">搜索</button>
                    </div>
                </form>
            </div>
        </div>
        {!! Form::close() !!}

        <div class="row" style="margin-top: 20px;">
            <div class="col-xs-12">
                <ul class="list-group">
                    @foreach($books as $book)
                        <a href="{{route('book.show', $book->id)}}" class="list-group-item">
                            <div>
                                {{$book->name}}<br>
                                <small style="color:#999">isbn: {{ $book->isbn }}</small>
                                <br>
                                <small style="color:#999">作者: {{$book->authors}}</small>
                            </div>
                        </a>
                    @endforeach
                </ul>
            </div>

        </div>
        <div class="row" style="text-align:center">
            {!! $books->appends(Input::except('page'))->links('vendor.pagination.default') !!}
        </div>
    </div>
@endsection
