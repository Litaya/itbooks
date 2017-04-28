@extends('admin.forum.layouts.layout_norefer')

@section('forum-content')

    <style>
        .ms-container{
            margin-left:auto;
            margin-right: auto;
        }
    </style>

    <h3>文章分类 &nbsp;&nbsp;&nbsp;&nbsp;
        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addCategory">添加分类</button>
        {{--<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addMaterialsToCategory">添加文章到本类别</button>--}}
    </h3>
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
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#alterCategory{{$category->id}}">修改类别名</button>
                        <button class="btn btn-danger btn-sm" onclick="removeCate({{ $category->id }})">删除类别</button>
                    </div>
                    <hr>
                    <div class="col-lg-12">
                        <ul class="list-group" id="cate{{ $category->id }}_table">

                        </ul>
                        <p id="cate{{ $category->id }}_load_more" class="hidden" style="text-align: center"><a href="javascript:void(0)">点击加载更多</a></p>
                    </div>
                </div>

                {{--修改分类名--}}
                <div class="modal fade" id="alterCategory{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="margin-top: 100px;">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">修改分类名</h4>
                            </div>
                            <form class="form form-horizontal" action="{{ route('category.altername') }}" method="post">

                                <div class="modal-body">
                                    {{ csrf_field() }}
                                    <input class="form-control" type="text" name="name" placeholder="输入分类名称" id="name">
                                    <input type="text" name="id" value={{$category->id}} class="hidden">
                                    <input style="display:none;">
                                    <p id="input_cate_hint" style="color:red"></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                    <button type="submit" class="btn btn-primary" >确认修改</button>
                                </div>
                            </form>

                        </div>
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



    <div>
    {{--添加文章到分类--}}
    {{--<div class="modal fade" id="addMaterialsToCategory" tabindex="-1" role="dialog" aria-labelledby="addMaterialsToCategoryLabel" style="margin-top: 100px;">--}}
        {{--<div class="modal-dialog" role="document">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                    {{--<h4 class="modal-title" id="addMaterialsToCategoryLabel">添加文章到本类别</h4>--}}
                {{--</div>--}}
                {{--<form class="form form-horizontal" action="#" method="post">--}}
                    {{--<div class="modal-body" style="padding:10px 40px;">--}}
                        {{--{{ csrf_field() }}--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="materials_cate">选择类别</label>--}}
                            {{--<select name="materials_cate" id="materials_cate">--}}
                                {{--@foreach($categories as $category)--}}
                                    {{--<option value={{ $category->id }}>{{ $category->name }}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="materials">选择文章</label>--}}
                            {{--<select class="searchable" multiple="multiple" id="materials" name="materials[]">--}}
                                {{--<option value='elem_1'>elem 1</option>--}}
                                {{--<option value='elem_2'>elem 2</option>--}}
                                {{--<option value='elem_3'>elem 3</option>--}}
                                {{--<option value='elem_4'>elem 4</option>--}}
                                {{--<option value='elem_100'>elem 100</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="modal-footer">--}}
                        {{--<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>--}}
                        {{--<button type="button" class="btn btn-primary" onclick="addMaterialsToCategory()">添加分类</button>--}}
                    {{--</div>--}}
                {{--</form>--}}

            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    </div>

    <script>
        jQuery(document).ready(function ($) {
            $('#materials').multiSelect();
            $('.searchable').multiSelect({
                selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='try \"12\"'>",
                selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='try \"4\"'>",
                afterInit: function(ms){
                    var that = this,
                        $selectableSearch = that.$selectableUl.prev(),
                        $selectionSearch = that.$selectionUl.prev(),
                        selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                        selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                        .on('keydown', function(e){
                            if (e.which === 40){
                                that.$selectableUl.focus();
                                return false;
                            }
                        });

                    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                        .on('keydown', function(e){
                            if (e.which == 40){
                                that.$selectionUl.focus();
                                return false;
                            }
                        });
                },
                afterSelect: function(){
                    this.qs1.cache();
                    this.qs2.cache();
                },
                afterDeselect: function(){
                    this.qs1.cache();
                    this.qs2.cache();
                }
            });
        });


        $('#myTabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show')
        });

        function loadMaterials(cate_id,page,per_page) {
            var url = "{{ route('api.material.catematerials') }}";
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
                        $("#cate"+cate_id+"_table").append("<li class='list-group-item'>"+data[i]['wechat_update_time']+"&nbsp;&nbsp;<a href='/admin/forum/material/"+data[i]['id']+"' target='_blank'>"+data[i]["title"]+"</a></li>");
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
        
        function alterCategory() {

        }

    </script>
@stop