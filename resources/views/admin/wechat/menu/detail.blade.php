@extends('admin.wechat.layout')

@section('wechat-content')
    <style>
        #menu-structure{
            background-color: #f6f6f6;

        }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h3>自定义菜单</h3>
            <hr>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="panel panel-default"  style="min-height: 400px;">
            <div class="panel-heading">
                菜单结构
            </div>
            <div class="panel-body">
                这里是自定义菜单的结构
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="panel panel-default">
            <div class="panel-body">
                这里是每个菜单项的详情
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                这里是菜单的预览图
            </div>
        </div>
    </div>
@stop
