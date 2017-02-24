@extends('admin.layouts.frame')

@section('title','书圈-后台管理系统')
@section('content')

    <div class="col-lg-12">
        <div class="col-lg-4 main-module active">
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

        <div class="col-lg-4 main-module" >
            <a href="javascript:void(0)">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1> <i class="fa fa-book push"></i></h1>
                        <h4>图书管理</h4>
                        <hr>
                        <small>待开放</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 main-module ">
            <a href="javascript:void(0)">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1> <i class="fa fa-institution push"></i></h1>
                        <h4>部门管理</h4>
                        <hr>
                        <small>待开放</small>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-12">&nbsp;
    </div>

    <div class="col-lg-6 usual-module">
        <div class="panel panel-primary">
            <div class="panel-heading">确认用户身份 &nbsp;&nbsp; <a href="javascript:void(0)"> <small>查看全部>></small></a></div>
            <div class="panel-body">
                <table class="table table-default" style="font-size: 12px;">
                    <tr>
                        <td>id</td>
                        <td>姓名</td>
                        <td>申请身份</td>
                        <td>工作单位/学校</td>
                        <td>申请时间</td>
                        <td>

                            操作
                            {{--<a href="javascript:void(0)"> <i class="fa fa-check"></i></a> &nbsp;--}}
                            {{--<a href="javascript:void(0)"> <i class="fa fa-times" style="color:red"></i></a> &nbsp;--}}
                            {{--<a href="javascript:void(0)"> <i class="fa fa-arrow-right" style="color:green"></i></a>--}}
                        </td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>张馨如</td>
                        <td>教师</td>
                        <td>北京航空航天大学</td>
                        <td>02-21 12:09:11</td>
                        <td>
                            <a href="javascript:void(0)" title="同意"> <i class="fa fa-check"></i></a> &nbsp;
                            <a href="javascript:void(0)" title="拒绝"> <i class="fa fa-times" style="color:red"></i></a> &nbsp;
                            <a href="javascript:void(0)" title="查看详情"> <i class="fa fa-arrow-right" style="color:green"></i></a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>


    <div class="col-lg-6 usual-module">
        <div class="panel panel-primary">
            <div class="panel-heading">样书申请 &nbsp;&nbsp;<a href="javascript:void(0)"> <small>查看全部>></small></a></div>
            <div class="panel-body">
                <table class=" table table-default" style="font-size: 12px;">
                    <tr>
                        <td>编号</td>
                        <td>姓名</td>
                        <td>身份</td>
                        <td>申请书籍</td>
                        <td>操作</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>张馨如</td>
                        <td>教师</td>
                        <td>编译原理第二版</td>
                        <td>
                            <a href="javascript:void(0)" title="同意"> <i class="fa fa-check"></i></a> &nbsp;
                            <a href="javascript:void(0)" title="拒绝"> <i class="fa fa-times" style="color:red"></i></a> &nbsp;
                            <a href="javascript:void(0)" title="查看详情"> <i class="fa fa-arrow-right" style="color:green"></i></a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

@stop