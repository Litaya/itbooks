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
            <small style="margin-right: 20px; padding: 5px 0">
                当前分类：
                <select name="category_id" id="select_category" class="hidden">
                    <option value="0">未分类</option>
                </select>
                <span id="category_name">{{ empty($material->category)?'未分类':$material->category->name }}</span>
                <a href="javascript:void(0)" class="smaller" onclick="alterCategory()" id="alter_category_btn">修改</a>
                <a href="javascript:void(0)" class="smaller hidden" onclick="saveCategory()" id="save_category_btn">保存</a>
                <a href="javascript:void(0)" class="smaller" data-toggle="modal" data-target="#alterCategory">添加分类</a>
            </small>
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
        {{--评论部分--}}
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

    <div class="modal fade" id="alterCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="margin-top: 100px;">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">添加分类</h4>
                </div>
                <form class="form form-horizontal" action="#" method="post">

                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input class="form-control" type="text" name="category_name" placeholder="输入分类名称" id="input_cate" onchange="inputCateChange()">
                        <input style="display:none;">
                        <p id="input_cate_hint" style="color:red"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-primary" onclick="addCategory()">添加分类</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        // 点击修改按钮事件
        function alterCategory() {
            var url = "{{ route('api.category.all') }}";
            $.ajax({
                method:'get',
                url:url,
                success:function (data) {
                    var categories = JSON.parse(data);
                    $("#select_category").html("");
                    for(var i =0;i<categories.length;i++){
                        var category = categories[i];
                        $("#select_category").append("<option value='"+category['id']+"'>"+category['name']+"</option>");
                    }
                }
            });

            $("#select_category").removeClass('hidden');
            $("#category_name").addClass('hidden');
            $("#alter_category_btn").addClass('hidden');
            $("#save_category_btn").removeClass('hidden');
        }

        // 点击保存分类按钮事件
        function saveCategory() {
            var url = "{{ route('admin.material.update_cate') }}";
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var post_data = {
                _token: CSRF_TOKEN,
                'material_id':'{{ $material->id }}',
                'category_id':$("#select_category").val()
            };
            console.log(post_data);
            $.ajax({
                method:'post',
                url:url,
                data:post_data,
                success:function (result) {
                    location.reload();
                }
            });
        }

        // 分类输入框文字变化事件
        function inputCateChange () {
            var url = "{{ route("api.category.exist") }}";
            $.ajax({
                method:'get',
                url:url,
                data:{
                    'cate_name': $("#input_cate").val()
                },
                success:function (result) {
                    if(result == 'success'){
                        $("#input_cate_hint").html("您输入的分类已存在");
                    }else{
                        $("#input_cate_hint").html("");
                    }
                }
            });
        }

        // 点击保存分类（添加分类的模态框中的分类）
        function addCategory() {
            var url = "{{ route("api.category.exist") }}";
            $.ajax({
                method:'get',
                url:url,
                data:{
                    'cate_name': $("#input_cate").val()
                },
                success:function (result) {
                    if(result == 'success'){
                        $("#input_cate_hint").html("您输入的分类已存在");
                    }else{
                        $("#input_cate_hint").html("");
                        var url = "{{ Route('category.create') }}";
                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                        var post_data = {
                            _token: CSRF_TOKEN,
                            'cate_name':$("#input_cate").val()
                        };
                        $.ajax({
                            method:'post',
                            url:url,
                            data: post_data,
                            success:function (result) {
                                console.log(result);
                                if(result == 'success')
                                    location.reload();
                                else
                                    $("#input_cate_hint").html("添加失败");
                            }
                        });
                    }
                }
            });
        }

        // 激活添加分类的模态框
        $('#alterCategory').on('shown.bs.modal', function () {
            $('#myInput').focus()
        });

        // 修改图文页面的显示问题
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
