@extends('layouts.frame')

@section('title', "修改认证信息")

@section('content')
    {!! Form::open(["route"=>"cert.update", "method"=>"PUT"]) !!}

    {!! Form::close() !!}
@stop