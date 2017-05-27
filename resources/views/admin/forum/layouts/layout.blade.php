@extends('admin.layouts.frame')

@section('title','书圈-后台管理系统')
@section('content')

    <style>
        #second_sider{
            padding: 30px 10px 0 10px;
            max-width: 120px;
        }
        #forum_content{
            padding: 20px 0 40px 50px;
        }
        ._side_item{
            text-align: left;
            padding-left: 5px;
        }

    </style>

    <div class="container" style="margin:-40px 0 -40px -50px;">
        <div class="col-lg-2" id="second_sider">
            @include('admin.forum.layouts._side')
        </div>
        <div class="col-lg-10" id="forum_content">
            @if(Session::has('forum_message'))
                <div class="col-lg-12">
                    <div class="panel panel-{{Session::has('forum_status')?Session::get('forum_status'):'default'}}">
                        <div class="panel-body">
                            {{ Session::get('forum_message') }}
                        </div>
                    </div>
                </div>
            @endif
            @yield('forum-content')
        </div>
    </div>
@stop