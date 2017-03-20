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

        .marginless {
            margin-left:  -20px;
            margin-right: -20px;
            padding-left:  -20px;
            padding-right:  -20px;
        }

    </style>

    <div class="container-fluid marginless">

    <div class="row" style="margin-left: -20px; margin-right: -20px;">
    {!! Form::open(["route"=>"book.index", "method"=>"GET"]) !!}
        <div class="col-xs-9 col-md-9" style="padding: 0; margin: 0;">
        {{ Form::text('search', null, ['placeholder'=>'发现更多好书', "class"=>"form-control", "style"=>"margin: 0;"]) }}
        </div>
        <div class="col-xs-3 col-md-3" style="padding: 0; margin: 0;">
        {{ Form::submit('搜索', ["class"=>"form-control", "style"=>"margin: 0;"]) }}
        </div>
    </div>
    {!! Form::close() !!}


    <div class="row marginless">

        <div class="col-xs-12">
            <div class="panel panel-default">
            
            <div class="panel-body" style="padding-left: 0px; padding-right: 0px;">
            
            <ul class="list-group">
                @foreach($books as $book)
                <a href="{{route('book.show', $book->id)}}" class="list-group-item">{{$book->name}}</a>
                @endforeach
            </ul>

            </div>
            </div>
        </div>

    </div>

    {!! $books->appends(Input::except('page'))->links() !!}
    
    
    </div>
    </div>

@endsection