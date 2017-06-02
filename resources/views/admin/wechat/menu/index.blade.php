@extends('admin.wechat.layout')
@section('wechat-content')
    <style>
        .menu-item-1{
            border:1px solid #cccccc;
            background-color: #f6f6f6;
            padding: 10px 5px;
            text-align: center;
        }
        .menu-item-2-wrapper{
            padding: 2px;
        }
        .menu-item-2{
            border:1px solid #cccccc;
            border-top: none;
            background-color: #ffffff;
            text-align: center;
            padding: 10px 1px;
            font-size: 14px;
        }
        .leftest{
            border-left: 1px solid #cccccc;
        }
        .menu-item-content{
            padding: 0;
            margin-top: 3px;
        }
    </style>
    <div class="row">
        <h3>自定义菜单 &nbsp;&nbsp;<small><a href="{{ route("admin.wechat.menu.create") }}"><i class="fa fa-plus push"></i>新建菜单模板</a></small></h3>
        <hr style="margin-bottom: 10px;">
    </div>
    <div class="row">
        @if(!@empty($menus))
            @foreach($menus as $menu)
                <div class="col-lg-6" style="margin-top: 20px;">
                    <div class="col-lg-12" style="padding:10px; background-color: #ffffff;box-shadow:1px 1px 5px #ccc; border: 1px solid #f2f2f2;">
                        <div class="col-lg-12 menu-detail" style="padding: 0; min-height: 310px;">
                            <div class="col-lg-12" style="padding:0;">
                                <h4>{{ $menu->title }}&nbsp;&nbsp;<small style="color: #00cc99; font-weight: bold">{{ $menu->status == 1?"正在使用中":"" }}</small></h4>
                                <hr style="margin: 15px 0;">
                            </div>
                            <div class="col-lg-12 menu-item-content">
                                @if(!@empty($menu->json->button))
                                    @foreach($menu->json->button as $button)
                                        <div class="col-lg-4 menu-item-2-wrapper">
                                            <div class="col-lg-12 menu-item-1 leftest">{{ $button->name}}</div>
                                            @if(!empty($button->sub_button))
                                                @foreach($button->sub_button as $sub_button)
                                                    @if($sub_button->type == 'view')
                                                        <div class="col-lg-12  menu-item-2">
                                                            <a href="{{ $sub_button->url }}">{{ $sub_button->name }}</a>
                                                        </div>
                                                    @else
                                                        <div class="col-lg-12  menu-item-2">
                                                            {{ $sub_button->name }}
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-12" style="padding:0;"><hr style="margin: 10px 0;"></div>
                        <div class="col-lg-12" style="padding: 0;">
                            <button class="btn btn-primary btn-sm" {{ $menu->status == 1?'disabled="disabled"':"" }} onclick="apply('{{ route('admin.wechat.menu.apply',$menu->id) }}')"><i class="fa fa-check push"></i>应用</button>
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.wechat.menu.detail',$menu->id) }}"><i class="fa fa-eye push"></i>查看详情</a>
                            <a class="btn btn-success btn-sm" href="{{ route('admin.wechat.menu.edit',$menu->id) }}"><i class="fa fa-edit push"></i>编辑</a>
                            <button class="btn btn-danger btn-sm" onclick="drop('{{ route('admin.wechat.menu.drop',$menu->id) }}')"><i class="fa fa-times push"></i>删除</button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <script>
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
                    location.reload();
                }
            });
        }
    </script>
@stop