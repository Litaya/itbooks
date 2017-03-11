@extends('layouts.frame')

@section('title', '最新会议')

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

    <div class="container-fluid">

    <div class="row marginless">
    <div class="col-xs-12">
        <div class="panel panel-default">
        <div class="panel-heading">会议列表</div>
        <div class="panel-body"> 
            <h2>{{$conferences[0]->name}}</h2>
            <div class="row">
            <div class="col-xs-6">
                <a href="{{route('conference.show', $conferences[0]->id)}}">
                <img class="img-thumbnail col-center-block" src="{{$conferences[0]->img_upload}}" alt="最近会议">
                </a>
            </div>

            <div class="col-xs-6">
                主办方: {{$conferences[0]->host}}<br>
                地点: {{$conferences[0]->location}}<br>
                时间: {{$conferences[0]->time}}<br>
                <a href="{{route('conference.show', $conferences[0]->id)}}">详情</a>
            </div>
            </div>
        
        </div>

        <ul class="list-group">
        @foreach($conferences as $c)
        <a href="{{route('conference.show', $c->id)}}" class="list-group-item">
        {{$c->name}}
        @if($c->time < date('Y-m-d'))
        <span style="color: #F77"><small>[已结束]</small></span>
        @else
        <span style="color: #77F"><small>[进行中]</small></span>
        @endif
        </a>
        @endforeach
        </ul>

        </div>
        </div>

    </div>

    </div>
    </div>

@endsection