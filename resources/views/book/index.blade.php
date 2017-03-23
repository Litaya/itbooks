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
            max-height: 65%;
        }

        .marginless {
            margin-left:  -20px;
            margin-right: -20px;
            padding-left:  -20px;
            padding-right:  -20px;
        }

    </style>

    <div class="container-fluid">

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
    </div>

    <div class="row marginless">

    <div class="col-xs-12">
        <div class="panel panel-default">
        
        @if(count($topbooks) > 0)
        <div class="panel-body" style="padding-left: 0px; padding-right: 0px;">
            <div class="col-xs-5">
                <a href="{{route('book.show', $topbooks[0]->id)}}"><img class="img-thumbnail img-in-well col-center-block" src="{{$topbooks[0]->img_upload}}" alt="编辑推荐"></a>
            </div>

            <div class="col-xs-7">
                <small>
                <p><strong>{{$topbooks[0]->name}}</strong></p>
                作者: {{$topbooks[0]->authors}}<br>
                定价: {{$topbooks[0]->price}}<br>
                <br>
                <p><small>{{$topbooks[0]->description}}</small></p>
                <small>
            </div>
        </div>
        @endif

        <ul class="list-group">
            <a data-toggle="collapse" href="#recommendCollapse" class="list-group-item">为您精选</a>
            <div class="collapse" id="recommendCollapse">
                <div class="well">
                        <div class="row">
                            @foreach($booksrecommend as $book)
                            <div class="col-xs-6" style="margin-left: 0; margin-right: 0;">
                            <div class="well well-showcase">
                            <a href="{{route('book.show', $book->id)}}"><img class="img-thumbnail img-in-well col-center-block" src="{{$book->img_upload}}" alt="Book0"></a>
                            <p>
                                <h2>{{$book->name}}</h2>
                            </p>
                            
                            </div>
                            </div>
                            @endforeach
                        </div>
                </div>
            </div>
            <a data-toggle="collapse" href="#newbookCollapse" class="list-group-item">最新上架</a>
            <div class="collapse" id="newbookCollapse">
                <div class="well">
                        <div class="row">    
                            @foreach($newbooks as $book)
                            <div class="col-xs-6">
                            <div class="well well-showcase">
                            <a href="{{route('book.show', $book->id)}}"><img class="img-thumbnail img-in-well col-center-block" src="{{$book->img_upload}}" alt="Book0"></a>
                            <p>
                                <h2>{{$book->name}}</h2>
                            </p>
                            
                            </div>
                            </div>
                            @endforeach
                        </div>
                </div>
            </div>
            <a data-toggle="collapse" href="#hotbookCollapse" class="list-group-item">热门图书</a>
            <div class="collapse" id="hotbookCollapse">
                <div class="well">
                        <div class="row" id="hotbookRow">    
                            @foreach($hotbooks as $book)
                            <div class="col-xs-6">
                            <div class="well well-showcase">
                            <a href="{{route('book.show', $book->id)}}"><img class="img-thumbnail img-in-well col-center-block" src="{{$book->img_upload}}" alt="Book0"></a>

                            <p>
                                <h2>{{$book->name}}</h2>
                            </p>
                            
                            </div>
                            </div>
                            @endforeach
                        </div>
                </div>
            </div>
        </ul>
        </div>
        </div>

    </div>



    
    
    </div>
    </div>

<script>

</script>

@endsection