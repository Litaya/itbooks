@extends('layouts.frame')

@section('title', '身份认证')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- IF THIS USER HAS AN UNFINISHED APPLICATION -->
                @if($selection == "exist")
                <div>
                <p>您已经提交过申请，当前状态为<strong>{{"正在审核"}}</strong></p>
                </div>
                @endif
            </div>
        </div>
    </div>

@endsection