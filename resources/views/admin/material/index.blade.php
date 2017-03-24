@extends('admin.layouts.frame')

@section('title','书圈-微信素材管理')
@section('content')
    <style>
        #pages{
            text-align: center;
        }
        #pages > .item{
            padding:5px 10px;
            color: #999;
            background-color: white;
            box-shadow: 1px 1px 3px #ccc;
        }
        #pages > .active{
            padding:5px 10px;
            color: #999;
            background-color: #FAFAFA;
            box-shadow: inset 0 0 3px #ccc;
        }
    </style>
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-lg-12">
            <small style="color:gray">今日阅读:23 &nbsp;&nbsp;今日评论:12</small>
            <form action="{{ route('admin.material.sync') }}" method="post" style="display: inline">
                {{ csrf_field() }}
                <input type="submit" href="javascript:void(0)"href="javascript:void(0)" class="btn btn-success btn-sm" style="position: absolute; right: 10px;" value="同步列表"/>
            </form>
        </div>
    </div>
    <div class="row" style="background-color: #ffffff; box-shadow:0 0 5px #ccc;margin-bottom: 10px;">
        <div class="col-lg-2" style="padding-left: 0;">
            <a href="javascript:void(0)"><img src="/img/example.jpg" alt="" height="100px;" width="100%;"></a>
        </div>
        <div class="col-lg-10" style="padding: 10px 0 0 0;height: 100px;">
            <p><a href="{{ route('admin.material.show',1) }}">雷军送武大一栋楼，武大回赠雷军一本书，它成就了雷军的百亿身价</a></p>
            <small>去年10月份，雷军向母校武汉大学捐赠了1亿元人民币用来建造科技楼。今天，武汉大学校长亲自到京拜访小米公司...</small>
            <br>
            <small style="position: absolute;bottom:5px; color:#ccc">阅读: 0&nbsp; 评论: 0</small>
            <small style="position: absolute;bottom:5px; right: 15px; color:#ccc">2017-12-01 12:00:12</small>
        </div>
    </div>
    <div class="row" id="pages">
        <a href="javascript:void(0)"class="item">1</a>
        <a href="javascript:void(0)"class="item active">2</a>
        <a href="javascript:void(0)"class="item">3</a>
    </div>
@endsection