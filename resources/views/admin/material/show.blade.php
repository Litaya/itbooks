@extends('admin.layouts.frame_norefer')

@section('title','书圈-微信素材管理')
@section('content')
    <style>
        .category-item{
            padding:5px 10px;
            border-radius: 5px;
            font-size: 10px;
            border: 1px solid #33CC99;
            color:#33CC99;
            margin-right: 5px;
        }
        .smaller{
            font-size:12px;
        }
        #comment-box{
            background-color: #ffffff;
            box-shadow: 0 0 5px #cccccc;
        }
        .shadow{
            box-shadow: 1px 1px 5px #ccc;
        }
    </style>
    <div class="row" >
        <h4>
            {{ $material->title }}&nbsp;&nbsp;&nbsp;&nbsp;
            <small class="smaller">阅读: <a href="javascript:void(0)">{{ $material->reading_quantity }}</a>&nbsp; 评论: <a href="javascript:void(0)">0</a>&nbsp; 收藏: <a href="javascript:void(0)">0</a>&nbsp; 来源: 微信</small>
        </h4>
        <p>
            <small style="margin-right: 20px; padding: 5px 0">分类：其他 <a href="javascript:void(0)" class="smaller">修改</a></small>
            <span class="category-item">标签1</span>
            <span class="category-item">标签2</span>
            <a href="javascript:void(0)"><span class="category-item"><i class="fa fa-plus"></i></span></a>
        </p>
        <hr>
    </div>
    <div class="row" >
        <div class="col-xs-7 shadow"style="background-color: white;">
            <div class="col-xs-12" id="body"></div>
        </div>
        <div class="col-xs-5">
            <div class="col-xs-12 shadow" style="background-color: white;">
                <h4>评论列表</h4>
                <hr>
                <div class="col-lg-12" style="padding: 0;height: 70px;">
                    <img src="/img/avatar.png" alt="" style="width:50px;height: 50px;border-radius: 25px; position: absolute; left: 0;"/>
                    <p style="position: absolute;left: 70px;"><a href="javascript:void(0)">张馨如</a>：雷哥很给力
                        <br>
                        <small>2015-01-01&nbsp;12:32:12</small>
                    </p>
                </div>
                <div class="col-lg-12" style="padding: 0;height: 70px;">
                    <img src="/img/avatar.png" alt="" style="width:50px;height: 50px;border-radius: 25px; position: absolute; left: 0;"/>
                    <p style="position: absolute;left: 70px;"><a href="javascript:void(0)">丛硕</a>回复<a href="javascript:void(0)">张馨如</a>：我同意
                        <br>
                        <small>2015-01-01&nbsp;12:32:12</small>
                    </p>
                </div>
            </div>
        </div>
        <hr>
    </div>
    <script>
        $("#body").html('<?php echo $material->content?>');
        $("[data-src]").each(function () {
            $(this).attr("src",$(this).attr('data-src'));
        });
        $("img[data-src]").each(function () {
            var obj_img_data_s = $(this).attr("data-s");
            if(obj_img_data_s){
                var max_width = parseInt(obj_img_data_s.split(",")[0]);
                if($(this).attr("data-w")>max_width){
                    $(this).attr("width",'100%');
                }
            }else{
                $(this).attr("width",'100%');
            }
        });
    </script>
@endsection
