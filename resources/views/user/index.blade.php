@extends('layouts.frame')

@section('title','用户中心')

@section('content')
    <div class="container">
        <div class="col-xs-12" style="text-align: center">
            <p><img src="{{ Auth::user()->headimgurl }}" alt="" style="width:100px; height: 100px; border-radius: 50px"></p>
            <p> {{ Auth::user()->username }}</p>
        </div>
        <div class="col-xs-12" style="padding:0;">
            <hr>

            <div class="list-group">
                <a href="{{ route('user.email') }}" class="list-group-item">
                    邮箱：{{ Auth::user()->email }}
                    <span style="position:absolute; right: 20px;">{{ Auth::user()->email?(Auth::user()->email_status?'已验证':'未验证'):"未填写" }} <i class="fa fa-angle-right" style="margin-left: 5px"></i></span>
                </a>
                <a class="list-group-item" href="{{ Auth::user()->certificate_as==""?route("cert.create"):(Auth::user()->certificate_as=="TEACHER"?route('userinfo.basic'):(Auth::user()->certificate_as=="AUTHOR"?"javascript:void(0)":"javascript:void(0)")) }}">
                    用户身份：{{ Auth::user()->certificate_as==""?"未知":(Auth::user()->certificate_as=="TEACHER"?"教师":(Auth::user()->certificate_as=="AUTHOR"?"作者":"未知")) }}
                    <span style="position:absolute; right: 20px;">{{ Auth::user()->certificate_as==""?'未认证':'已认证' }}<i class="fa fa-angle-right" style="margin-left: 5px"></i></span>
                </a>
                <a class="list-group-item" href="{{ route("user.address.index") }}">地址：{{ isset(json_decode(Auth::user()->json_content,true)['address']['location'])?json_decode(Auth::user()->json_content,true)['address']['location']:"未填写" }}</a>
                <a class="list-group-item" href="javascript:void(0)">电话：{{ isset(Auth::user()->userInfo->phone)?Auth::user()->userInfo->phone:"暂未填写" }}</a>
                <a class="list-group-item" href="javascript:void(0)">QQ号：{{ isset(Auth::user()->userInfo->qq)?Auth::user()->userInfo->qq:"暂未填写" }}</a>

            </div>
        </div>
    </div>
@stop