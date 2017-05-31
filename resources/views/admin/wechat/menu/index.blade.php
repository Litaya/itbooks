@extends('admin.wechat.layout')

@section('wechat-content')
    <style>
        .menu-item-1{
            border:1px solid #cccccc;
            border-left: none;
            background-color: #f6f6f6;
            padding: 10px 5px;
            text-align: center;
        }
        .leftest{
            border-left: 1px solid #cccccc;
        }
    </style>
    <div class="row">
        <div class="col-lg-4" style="min-height: 200px;">
            <div class="col-lg-12"></div>
            <div class="col-lg-12" style="padding: 0;">
                <div class="col-lg-4 menu-item-1 leftest">
                    <span class="push">活动</span>
                </div>
                <div class="col-lg-4 menu-item-1">
                    图书
                </div>
                <div class="col-lg-4 menu-item-1">
                    文章
                </div>
            </div>
        </div>
    </div>
@stop