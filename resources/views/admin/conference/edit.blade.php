@extends('admin.layouts.frame')

@section('title', '会议管理 | '.$conference->name)

@section('content')
<div class="container">
    <div class="row">
    <div class="col-md-8">
        {!! Form::model($conference, ['route'=>['admin.conference.update', $conference->id], 'method'=>'PUT']) !!}
        {{ Form::label('name', '会议名称')}} {{Form::text('name', null, ['class'=>'form-control'])}}
        {{ Form::label('time', '时间')}} {{Form::text('time', null, ['class'=>'form-control'])}}
        {{ Form::label('location', '地点')}} {{Form::text('location', null, ['class'=>'form-control'])}}
        {{ Form::label('host', '主办方')}} {{Form::text('host', null, ['class'=>'form-control'])}}
        {{ Form::label('detail_url', '参会须知(url)')}} {{Form::text('detail_url', null, ['class'=>'form-control'])}}
        {{ Form::label('description', '简介')}} {{Form::textarea('description', null, ['class'=>'form-control'])}}
        {{ Form::submit('保存修改', ['class'=>'btn btn-success'])}}
        {!! Form::close() !!}
        <button class="btn btn-default" onclick="window.history.back();">放弃修改</button>
    </div>

    <div class="col-md-3 col-md-offset-1">
        <p>创建时间: {{date('Y-m-d', strtotime($conference->created_at))}}</p>
        <p>修改时间: {{date('Y-m-d', strtotime($conference->updated_at))}}</p>
        <div class="row">
        <div class="col-md-6">
        <button class="btn btn-default" onclick="window.history.back();">返回上页</button>
        </div>
        <div class="col-md-6">
        {!! Form::open(['route'=>['admin.conference.destroy', $conference->id], 'method'=>'DELETE']) !!}
        {{ Form::submit("删除会议", ['class'=>"btn btn-danger"]) }}
        {!! Form::close() !!}
        </div>
        </div>
    </div>

    </div>
</div>
@endsection