@extends('admin.layouts.frame')

@section('title','书圈-后台管理系统')
@section('content')

    <style>
        #wechat_sider{
            padding: 30px 10px 0 10px;
            max-width: 120px;
        }
        #wechat_content{
            padding: 20px 0 40px 50px;
        }
        ._side_item{
            text-align: left;
            padding-left: 5px;
        }
    </style>

    <div class="container" style="margin:-40px 0 -40px -50px;">
        <div class="col-lg-2" id="wechat_sider">
            <h4><a href="{{ route('admin.wechat.index') }}"><i class="fa fa-weixin push"></i>微信管理</a></h4>
            <hr>
            <ul>
                <li class="_side_item"><a href="{{ route('admin.wechat.module.index') }}"><i class="fa fa-th push"></i>功能模块</a></li>
                <li class="_side_item"><a href="{{ route('admin.wechat.auto_reply.index') }}"><i class="fa fa-reply push"></i>自定义回复</a></li>
            </ul>
        </div>
        <div class="col-lg-10" id="wechat_content">
            @if(Session::has('wechat_message'))
                <div class="col-lg-12">
                    <div class="panel panel-{{Session::has('wechat_status')?Session::get('wechat_status'):'default'}}">
                        <div class="panel-body">
                            {{ Session::get('wechat_message') }}
                        </div>
                    </div>
                </div>
            @endif
            @yield('wechat-content')
        </div>
    </div>
@stop