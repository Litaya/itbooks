@extends('admin.forum.layouts.layout_norefer')

@section('forum-content')

    <h3>文章分类 <small><a href="javascript:void(0)" class="smaller" data-toggle="modal" data-target="#addCategory">添加分类</a></small></h3>
    <hr>

    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist" id="myTabs">
            @foreach($categories as $category)
                <li role="presentation">
                    <a href="#cate_{{ $category->id }}" aria-controls="cate_{{ $category->id }}" role="tab" data-toggle="tab"
                       onclick="loadMaterials('{{ $category->id }}',1,20)">
                        {{ $category->name }}({{ sizeof($category->materials) }})
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            @foreach($categories as $category)
                <div role="tabpanel" class="tab-pane" id="cate_{{ $category->id }}"  style=" padding-top: 20px;">
                    <div class="col-lg-12">
                        <button class="btn btn-success btn-sm">添加文章到本类别</button>
                        <button class="btn btn-primary btn-sm">修改类别名</button>
                        <button class="btn btn-danger btn-sm" onclick="removeCate({{ $category->id }})">删除本类别</button>
                    </div>
                    <hr>
                    <div class="col-lg-12">
                        <table class="table table-bordered table-hover" id="cate{{ $category->id }}_table" style="background-color: #ffffff">
                        </table>
                        <p id="cate{{ $category->id }}_load_more" class="hidden" style="text-align: center"><a href="javascript:void(0)">点击加载更多</a></p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="margin-top: 100px;">
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
        $('#myTabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show')
        });

        function loadMaterials(cate_id,page,per_page) {
            var url = "{{ route('material.catematerials') }}";
            $.ajax({
                method:'get',
                url:url,
                data: {
                    cate_id: cate_id,
                    page: page,
                    per_page: per_page
                },
                success:function (data) {
                    if(page == 1)
                        $("#cate"+cate_id+"_table").html("");
                    data = JSON.parse(data);
                    var length = data.length;
                    for(var i=0;i<length;i++){
                        $("#cate"+cate_id+"_table").append("<tr><td><small>"+ data[i]['wechat_update_time'] +"</small> &nbsp;&nbsp;"+data[i]["title"]+"</td></tr>");
                    }
                    if(length == per_page)
                        $("#cate"+cate_id+"_load_more").removeClass("hidden").html("<a href='javascript:void(0)' onclick='loadMaterials("+cate_id+","+(page+1)+","+per_page+")'>点击加载更多</a>");
                }
            });
        }

        function removeCate(cate_id) {
            var url = "{{ route('category.drop') }}";
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type:"DELETE",
                url:url,
                data:{
                    _token:CSRF_TOKEN,
                    cate_id: cate_id
                },
                success:function (data) {
                    location.reload();
                }
            })
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
    </script>
@stop