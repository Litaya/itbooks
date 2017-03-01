@extends('admin.layouts.frame')

@section('title', '会议管理 | 创建会议')

@section('content')
<div class="container">
    <div class="row">
    <div class="col-md-12">
        {!! Form::open(['route'=>'admin.conference.store', 'method'=>'POST']) !!}
        {{ Form::label('name', '会议名称')}} {{Form::text('name', null, ['class'=>'form-control'])}}
        {{ Form::label('time', '时间')}} {{Form::text('time', null, ['class'=>'form-control'])}}
        {{ Form::label('location', '地点')}} {{Form::text('location', null, ['class'=>'form-control'])}}
        {{ Form::label('host', '主办方')}} {{Form::text('host', null, ['class'=>'form-control'])}}
        {{ Form::label('detail_url', '参会须知(url)')}} {{Form::text('detail_url', null, ['class'=>'form-control'])}}
        {{ Form::label('description', '简介')}} {{Form::textarea('description', null, ['class'=>'form-control'])}}
        {{ Form::submit('创建', ['class'=>'btn btn-success'])}}
        {!! Form::close() !!}
        <button class="btn btn-default" onclick="window.history.back();">取消</button>
    </div>
    </div>
</div>
@endsection