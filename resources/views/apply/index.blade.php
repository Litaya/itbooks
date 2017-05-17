@extends('layouts.frame')

@section('title','书圈')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            身份绑定
        </div>
        <div class="panel-body">
            请选择您要绑定的身份 <br>
            <p></p>

            <div class="col-xs-3 apply_module">
                <a href="javascript:void(0)">
                    <i class="fa fa-user"></i> <br>
                    教师
                </a>
            </div>
            <div class="col-xs-3 apply_module">
                <a href="javascript:void(0)">
                    <i class="fa fa-book"></i> <br>
                    作者
                </a>
            </div>
            <div class="col-xs-3 apply_module">
                <a href="javascript:void(0)">
                    <i class="fa fa-pencil"></i> <br>
                    编辑
                </a>
            </div>

        </div>
    </div>
@stop