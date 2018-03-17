@extends('layouts.frame')

@section('title', '订单反馈')

@section('content')
    <div class="container">
        <div class="row">
            <h4>订购反馈申请 <small><a href="{{ route('order_fb.records') }}">点此查看历史申请</a></small></h4>
            <hr>
            <div class="panel panel-default">
                <div class="panel-body" style="font-size:12px;">
                    <strong style="color:orangered;font-size:14px;">申请须知</strong> <br>
                    1. 一年内同一书号只能上传一次订购信息 <br>
                    2. 订购信息被通过，则本年度样书申请额度+1 <br>
                    3. 用户本年样书申请额度不超过12本 <br>
                    4. 一年内没有上传订购信息的，减少2本申请数量，减少到2本为止
                </div>
            </div>
        </div>

        <div class="row">
            <form action="{{ route('order_fb.submit') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="isbn">订购书号</label>
                    <input type="text" class="form-control" name="isbn" id="isbn" placeholder="请输入教材isbn后六位">
                </div>
                <div class="form-group" style="width: auto;">
                    <label for="order_datetime">订购日期</label>
                    <input type="text" class="form-control" name="order_datetime" value="{{ date("Y-m-d",time()-86400) }}" readonly id="order_datetime">
                </div>
                <div class="form-group">
                    <label for="count">订购数量</label>
                    <input type="number" class="form-control" name="count" id="count" placeholder="请输入您订购的数量">
                </div>
                {{ Form::text('image_media_id',null,["id"=>"image_media_id","hidden"=>"hidden"]) }}
                <div class="form-group">
                    <small style="font-weight: bold"> 上传提交给教学秘书或者教务处今年选用教材的表格截图</small>
                    <a href="javascript:void(0)" class="btn btn-default btn-sm" onclick="chooseImage()">选择图片</a>
                </div>
                {{ Form::submit("提交", ["class"=>"btn btn-primary btn-block form-spacing-top"])}}

            </form>
        </div>

        <script type="text/javascript" src="/laydate/laydate.js"></script>
        <script type="text/javascript">
            wx.config(<?php echo $js->config(array('chooseImage', 'uploadImage'), false) ?>);

            laydate.render({
                elem: '#order_datetime' //指定元素
            });

            function chooseImage(){
                wx.chooseImage({
                    count: 1, // 默认9
                    sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                    sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                    success: function (res) {
                        var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                        setTimeout(function () {
                            wx.uploadImage({
                                localId: localIds.toString(), // 需要上传的图片的本地ID，由chooseImage接口获得
                                isShowProgressTips: 1, // 默认为1，显示进度提示
                                success: function (res) {
                                    alert('图片上传成功!');
                                    var serverId = res.serverId; // 返回图片的服务器端ID
                                    $("#image_media_id").val(serverId);
                                }
                            });
                        },100);
                    }
                });
            }
        </script>
    </div>

@endsection
