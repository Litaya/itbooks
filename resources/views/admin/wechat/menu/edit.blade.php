@extends('admin.wechat.layout')
@section('title','书圈-编辑菜单模板')
@section('wechat-content')
    <div class="row">
        <h3>编辑菜单模板</h3>
        <hr>
    </div>
    <style>
        .menu-item-1-create{
            border:1px dashed #00CC99;
            color: #00CC99;
            background-color: #ffffff;
            padding: 10px 5px;
            text-align: center;
        }
        .menu-item-2-create{
            border:1px dashed #00CC99;
            color: #00CC99;
            border-top: none;
            background-color: #ffffff;
            text-align: center;
            padding: 10px 1px;
            font-size: 14px;
        }
    </style>
    <div class="row" id="menu_structure">
        <div class="col-lg-6">
            <div class="col-lg-12">
                <div class="panel panel-default"  style="min-height: 350px;">
                    <div class="panel-heading">
                        菜单结构
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-12 menu-detail" style="padding: 0; min-height: 250px;">
                            <div class="col-lg-12 menu-item-content">
                                <div class="col-lg-4 menu-item-wrapper" v-for="btn in menu.button">
                                    <div class="col-lg-12 menu-item-1 leftest" onclick="bindMainDetail(@{{ $index }} )">@{{ btn.name }}</div>
                                    <div class="col-lg-12  menu-item-2" v-for="sub_btn in btn.sub_button">
                                        <a href="javascript:void(0)" onclick="bindSubDetail(@{{ $parent.$index }},@{{ $index }})">@{{ sub_btn.name }}</a>
                                    </div>
                                    <div class="col-lg-12 menu-item-2-create" v-if="btn.sub_button.length<5" onclick="addSubMenu(@{{ $index }})"><i class="fa fa-plus"></i></div>
                                </div>
                                <div class="col-lg-4 menu-item-wrapper"  v-if="menu.button.length < 3">
                                    <div class="col-lg-12 menu-item-1-create" onclick="addMainMenu()"><i class="fa fa-plus"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="col-lg-12">
                <label for="menu_title">菜单模板名</label>
                <input type="text" id="menu_title" name="menu_title" class="form-control" v-model="title"><br>
            </div>
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">菜单详情</div>
                    <div class="panel-body" id="menu-detail" v-model="btn_detail">
                        <form action="{{route('index')}}" class="form form-horizontal" onsubmit="return false" style="padding:0 20px;">
                            <div class="form-group" v-if="btn_detail.type!=-1">
                                <label for="menu_name">菜单名</label>
                                <input class="form-control" type="text" name="menu_name" v-model="btn_detail.detail.name">
                            </div>
                            <div v-if="(btn_detail.type == 1 && btn_detail.detail.sub_button.length == 0) || btn_detail.type == 2">
                                <hr>
                                <div class="form-group">
                                    <label for="menu_type">菜单类型</label>
                                    <select name="menu_type" id="menu_type" class="form-control" v-model="btn_detail.detail.type">
                                        <option value="template" v-if="btn_detail.detail.type == 'template'" selected>请选择</option>
                                        <option value="template" v-if="btn_detail.detail.type != 'template'">请选择</option>
                                        <option value="view" v-if="btn_detail.detail.type == 'view'" selected>跳转链接</option>
                                        <option value="view" v-if="btn_detail.detail.type != 'view'">跳转链接</option>
                                        {{--<option value="click" v-if="btn_detail.detail.type == 'click'" selected>回复消息</option>--}}
                                        {{--<option value="click" v-if="btn_detail.detail.type != 'click'">回复消息</option>--}}
                                    </select>
                                </div>
                                <div class="form-group" v-if="btn_detail.detail.type == 'view'">
                                    <label for="menu_url" >目标链接</label>
                                    <input type="text" class="form-control" name="menu_url" id="menu_url" v-model="btn_detail.detail.url">
                                </div>
                                <div class="form-group"  v-if="btn_detail.detail.type == 'click'">
                                    该菜单由开发者后台处理事件， key为@{{ btn_detail.detail.key }}
                                    {{--<label for="menu_reply" >回复内容</label>--}}
                                    {{--<textarea class="form-control" name="menu_reply" id="menu_reply" cols="30" rows="5" v-model="btn_detail.detail.key"></textarea>--}}
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="panel panel-footer" style="margin: 0;">
                        <button class="btn btn-success btn-sm" onclick="saveDetail()"><i class="fa fa-save push"></i>保存</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteButton()"><i class="fa fa-times push"></i>删除</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <button class="btn btn-success" onclick="postSave()"><i class="fa fa-save push"></i>保存</button>
        </div>
    </div>
    <p hidden id="decoder">hi</p>
    <script>
        $("#decoder").html('{{ $menu->json }}');
        var menu = JSON.parse($("#decoder").text());
        var vue = new Vue({
            el: '#menu_structure',
            data:{
                menu: menu,
                btn_detail: {
                    type: -1,
                    main_index: -1,
                    sub_index: -1,
                    detail:{},
                    btn_type: -1 // 0表示链接跳转，1表示自定义回复
                },
                title: '{{$menu->title}}'
            }
        });

        function addMainMenu() {
            vue._data.menu.button.push({
                name: "主菜单",
                sub_button: []
            })
        }
        function addSubMenu(index){

            vue._data.menu.button[index].sub_button.push({
                type: 'view',
                name: '二级菜单',
                url: 'http://www.itshuquan.com/material/6317',
                sub_button:[]
            })
        }

        function initDetail() {
            vue._data.btn_detail.detail     = {};
            vue._data.btn_detail.type       = 1;
            vue._data.btn_detail.main_index = -1;
            vue._data.btn_detail.sub_index  = -1;
        }

        // 绑定为一级菜单信息
        function bindMainDetail(index) {
            vue._data.btn_detail.type = 1;
            vue._data.btn_detail.main_index = index;
            vue._data.btn_detail.detail = vue._data.menu.button[index];
        }

        function bindSubDetail(main_index, index) {
            vue._data.btn_detail.type = 2;
            vue._data.btn_detail.main_index = main_index;
            vue._data.btn_detail.sub_index = index;
            vue._data.btn_detail.detail = vue._data.menu.button[main_index].sub_button[index];
        }

        function saveDetail() {
            if(vue._data.btn_detail.type == 1){
                vue._data.menu.button[vue._data.btn_detail.main_index] = vue._data.btn_detail.detail;
            }else{
                vue._data.menu.button[vue._data.btn_detail.main_index].sub_button[vue._data.btn_detail.sub_index] = vue._data.btn_detail.detail;
            }
            initDetail();
            console.log(vue._data.menu);
        }

        function deleteButton() {
            if(vue._data.btn_detail.type == 1){
                vue._data.menu.button.splice(vue._data.btn_detail.main_index,1);
            }else{
                vue._data.menu.button[vue._data.btn_detail.main_index].sub_button.splice(vue._data.btn_detail.sub_index,1);
            }
            initDetail();
        }

        function postSave() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var flag = true;
            vue._data.menu.button.forEach(function (button) {
                if(button.sub_button.length == 0 && (!button.hasOwnProperty('type'))){
                    alert('请完整填写菜单 '+button.name+' 的信息');
                }
            });
            $.ajax({
                method:'post',
                url:"{{ route('admin.wechat.menu.saveEdit',$menu->id) }}",
                data: {
                    _token: CSRF_TOKEN,
                    menu: vue._data.menu,
                    title: vue._data.title
                },
                success:function (menu_id) {
                    location.reload();
                }
            });
        }
    </script>
@stop
