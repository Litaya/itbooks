@extends('layouts.frame')

@section('title','课件密码')

@section('content')
    <div class="col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-heading">课件密码查询功能</div>
            <div class="panel-body">
                <p>请在公众号后台按下面格式回复</p>
                <ul>
                    <li>课件#书号，例如:课件#9787302307488</li>
                    <li>密码#书号，例如:9787302307488</li>
                    <li>书号，例如:9787302307488</li>
                </ul>

                <p style="font-size: 12px;">注：<br>
                （1）书号是封底的ISBN号（13位数字，不用加横线） <br>
                （2）不要在#前后加空格 <br>
                （3）点击微信公众号界面下方的小键盘图标，可以在文本框中输入回复内容
                </p>
            </div>
        </div>
    </div>
@endsection