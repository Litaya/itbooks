@extends('admin.layouts.frame')

@section('title','书圈-后台管理系统')
@section('content')

    <div class="col-lg-12">
        <div class="col-lg-4 main-module">
            <a href="{{ route('admin.user.index') }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1> <i class="fa fa-user push"></i></h1>
                        <h4>用户中心</h4>
                        <hr>
                        <small>今日注册&nbsp;10&nbsp;普通用户,&nbsp;2&nbsp;教师用户</small>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-4 main-module">
            <a href="javascript:void(0)">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1> <i class="fa fa-book push"></i></h1>
                        <h4>图书管理</h4>
                        <hr>
                        <small>今日申请&nbsp;10&nbsp;本样书,入库&nbsp;4&nbsp;本新书</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 main-module">
            <a href="javascript:void(0)">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1> <i class="fa fa-institution push"></i></h1>
                        <h4>部门管理</h4>
                        <hr>
                        <small>今日新增&nbsp;0&nbsp;部门</small>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-12">&nbsp;
    </div>

    <div class="col-lg-6 usual-module">
        <div class="panel panel-primary">
            <div class="panel-heading">确认用户身份</div>
            <div class="panel-body">
                <table class="table table-default" style="font-size: 12px;">
                    <tr>
                        <td>1</td>
                        <td>张馨如</td>
                        <td>教师</td>
                        <td>北京航空航天大学</td>
                        <td>02-09 12:00</td>
                        <td>
                            <a href="javascript:void(0)"> <i class="fa fa-check"></i></a> &nbsp;
                            <a href="javascript:void(0)"> <i class="fa fa-times" style="color:red"></i></a> &nbsp;
                            <a href="javascript:void(0)"> <i class="fa fa-arrow-right" style="color:green"></i></a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>


    <div class="col-lg-6 usual-module">
        <div class="panel panel-primary">
            <div class="panel-heading">样书申请</div>
            <div class="panel-body">

            </div>
        </div>
    </div>

@stop