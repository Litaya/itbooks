@extends('admin.wechat.layout')

@section('wechat-content')
    <style>
        #menu-structure{
            background-color: #f6f6f6;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h3>{{ $menu->title }}</h3>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <button class="btn btn-primary btn-sm" onclick="apply('{{ route('admin.wechat.menu.apply',$menu->id) }}')"><i class="fa fa-check push"></i>应用</button>
            <a class="btn btn-success btn-sm" href="{{ route('admin.wechat.menu.edit',$menu->id) }}"><i class="fa fa-edit push"></i>编辑</a>
            <button class="btn btn-danger btn-sm" onclick="drop('{{ route('admin.wechat.menu.drop',$menu->id) }}')"><i class="fa fa-times push"></i>删除</button>
        </div>
    </div>
    <div class="row" style="margin-top: 20px;">
        <div class="col-lg-5">
            <div class="panel panel-default"  style="min-height: 350px;">
                <div class="panel-heading">
                    菜单结构
                </div>
                <div class="panel-body">
                    <div class="col-lg-12 menu-detail" style="padding: 0; min-height: 250px;">
                        <div class="col-lg-12 menu-item-content">
                            @if(!@empty($menu->json->button))
                                @foreach($menu->json->button as $button)
                                    <div class="col-lg-4 menu-item-wrapper">
                                        <div class="col-lg-12 menu-item-1 leftest" onclick="clickItem('{{ json_encode($button) }}')">{{ $button->name}}</div>
                                        @if(!empty($button->sub_button))
                                            @foreach($button->sub_button as $sub_button)
                                                <div class="col-lg-12 menu-item-2">
                                                    <a href="javascript:void(0)" onclick="clickItem('{{ json_encode($sub_button) }}')">{{ $sub_button->name }}</a>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="panel panel-primary">
                <div class="panel-heading">菜单项详情</div>
                <div class="panel-body" id="item_detail">
                    <p class="item_detail_line">
                        <strong>菜单名&nbsp;</strong><span class="item_detail_general" id="item_detail_name"></span>&nbsp;&nbsp;&nbsp;&nbsp;
                        <strong>类型&nbsp;</strong><span class="item_detail_general" id="item_detail_type"></span>
                    </p>
                    <p class="item_detail_line">
                        <strong>详细参数</strong>
                    </p>
                    <div class="col-lg-12 item_detail_line" id="item_detail_detail">
                    </div>
                </div>
            </div>
        </div>
        <script>
            function clickItem(sub_button) {
                sub_button = JSON.parse(sub_button);
                $("#item_detail_name").html(sub_button.name);
                switch (sub_button.type){
                    case 'view':
                        $("#item_detail_type").html('跳转链接');
                        $("#item_detail_detail").html('<strong>url:</strong>&nbsp;'+sub_button.url);
                        break;
                    case 'click':
                        $("#item_detail_type").html('点击事件');
                        $("#item_detail_detail").html('本按钮交由开发者进行逻辑处理，按钮的触发key为: <strong class="item_detail_general">'+sub_button.key+'</strong>');
                        break;
                    default:
                        $("#item_detail_type").html("");
                        $("#item_detail_detail").html("");
                        break;
                }
                console.log(sub_button);
            }
            function apply(url) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    method:'post',
                    url:url,
                    data: {
                        _token: CSRF_TOKEN
                    },
                    success:function () {
                        location.reload();
                    }
                });
            }
            function drop(url) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    method:'post',
                    url:url,
                    data: {
                        _token: CSRF_TOKEN
                    },
                    success:function () {
                        window.location="{{ route('admin.wechat.menu.index') }}";
                    }
                });
            }
        </script>
    </div>
@stop
