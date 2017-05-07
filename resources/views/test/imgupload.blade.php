@extends('layouts.frame')

@section('title','测试微信图片上传')

@section('content')
    <form action="{{ route('test.saveimage') }}" method="post">
        {{ csrf_field() }}
        <input type="text" name="img_upload_id" id="img_upload_id" onchange="upload()">
        <input type="text" name="img_media_id" id="img_media_id" hidden>
    </form>

    <button class="btn btn-default" id="img_upload" onclick="choosImage()">点此选择要上传的图片</button>

    <script>
        wx.config(<?php echo $js->config(array('chooseImage', 'uploadImage'), true)?>);

        function choosImage(){
            wx.chooseImage({
                count: 1, // 默认9
                sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                success: function (res) {
                    var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                    $("#img_upload_id").val(localIds) ;
					setTimeout(function () {
                        wx.uploadImage({
                            localId:localIds.toString(), // 需要上传的图片的本地ID，由chooseImage接口获得
                            isShowProgressTips: 1, // 默认为1，显示进度提示
                            success: function (res) {
                                var serverId = res.serverId; // 返回图片的服务器端ID
                                $("#img_media_id").attr('value',serverId);
                                alert(serverId);
                            }
                        })
						
                    },1200);
                }
            });
        }

		function upload(){
            var localIds = $("#img_upload_id").val()
			wx.uploadImage({
                localId:localIds, // 需要上传的图片的本地ID，由chooseImage接口获得
                isShowProgressTips: 1, // 默认为1，显示进度提示
                success: function (res) {
                    var serverId = res.serverId; // 返回图片的服务器端ID
                    $("#img_media_id").attr('value',serverId);
                    alert(serverId);
                }
            });
		}

    </script>

@stop
