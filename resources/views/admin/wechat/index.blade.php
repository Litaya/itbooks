@extends('admin.wechat.layout')

@section('wechat-content')

    <style>
        table{
            background-color: #ffffff;
            box-shadow: 1px 1px 5px #ccc;
            text-align: center;
        }
        .td-open{
            color: green;
        }
        .td-closed{
            color: red;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <h3>功能模块 <small style="font-size: 12px;">&nbsp;&nbsp;已加载&nbsp;&nbsp;{{ sizeof($wechat_modules) }}&nbsp;&nbsp;个功能模块</small></h3>
            <hr>
            <table class="table table-bordered table-hover">
                <tr style="font-weight: bold">
                    <td>模块</td>
                    <td>名称</td>
                    <td>权重</td>
                    <td>当前状态</td>
                    <td>操作</td>
                </tr>
                @foreach($wechat_modules as $wechat_module)
                    <tr>
                        <td>{{ $wechat_module->module }}</td>
                        <td>{{ $wechat_module->name }}</td>
                        <td>{{ $wechat_module->weight }}</td>
                        <td class="td-{{ $wechat_module->status == 1?'open':'closed' }}" >{{ $wechat_module->status == 1?'开启':'关闭' }}</td>
                        <td class="td-{{ $wechat_module->status == 1?'closed':'open' }}" ><a
                                    href="javascript:void(0)" onclick="changeWechatModuleStatus({{ $wechat_module->id.','.$wechat_module->status }})">
                                {{ $wechat_module->status == 1?'关闭':'开启' }}
                            </a></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h3>自动回复 <small style="font-size: 12px;">&nbsp;&nbsp;已加载&nbsp;&nbsp;{{ sizeof($wechat_auto_replies) }}&nbsp;&nbsp;条回复规则</small></h3>
            <hr>
            @if(sizeof($wechat_auto_replies)>0)
                <table class="table table-bordered table-hover">
                    <tr style="font-weight:bold;">
                        <td style="min-width: 80px;">回复规则</td>
                        <td style="min-width: 80px;">回复类型</td>
                        <td>回复内容</td>
                        <td style="min-width: 80px;">触发次数</td>
                        <td style="min-width: 120px;">操作</td>
                    </tr>
                    @foreach($wechat_auto_replies as $wechat_auto_reply)
                        <tr>
                            <td>{{ $wechat_auto_reply->regex }}</td>
                            <td>{{ $wechat_auto_reply->type==0?"文字":($wechat_auto_reply->type==1?"图片":"图文") }}</td>
                            <td>{{ $wechat_auto_reply->content }}</td>
                            <td>{{ $wechat_auto_reply->trigger_quantity }}</td>
                            <td>
                                <button class="btn btn-success btn-xs">修改</button> &nbsp;&nbsp;
                                <button class="btn btn-danger btn-xs" onclick="removeAutoReply('{{ route('admin.wechat.auto_reply.destroy',$wechat_auto_reply->id) }}')">删除</button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>
    </div>
    <script type="text/javascript">
        function changeWechatModuleStatus(module_id, module_status) {
            module_status = module_status == 1?0:1;
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var url = "{{ route('admin.wechat.module.changestatus') }}";
            $.ajax({
                method:'post',
                url:url,
                data: {
                    _token: CSRF_TOKEN,
                    module_id: module_id,
                    module_status: module_status
                },
                success:function () {
                    location.reload();
                }
            });
        }

        function removeAutoReply(url) {
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