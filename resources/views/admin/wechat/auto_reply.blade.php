@extends('admin.wechat.layout')

@section('wechat-content')

    <script src="/js/jquery-plugin.js"></script>

    <link rel="stylesheet" href="/editors/css/wangEditor.min.css">
    <style>
        table{
            background-color: #fff;
            box-shadow: 1px 1px 5px #ccc;
        }
    </style>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h3>自动回复 <small style="font-size: 12px;">&nbsp;&nbsp;已加载&nbsp;&nbsp;{{ sizeof($wechat_auto_replies) }}&nbsp;&nbsp;条回复规则</small>
                <button type="button" class="btn btn-success btn-sm" data-target="#myModal" onclick="showModal('myModal')">
                    <i class="fa fa-plus push"></i>添加规则
                </button>
            </h3>
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
                                <button class="btn btn-success btn-xs" data-target="#alterReply" onclick="showModal('alterReply', {{ $wechat_auto_reply->id }},'{{$wechat_auto_reply->regex}}')">修改</button> &nbsp;&nbsp;
                                <button class="btn btn-danger btn-xs" onclick="removeAutoReply('{{ route('admin.wechat.auto_reply.destroy',$wechat_auto_reply->id) }}')">删除</button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>
    </div>

    <!-- Modal 添加规则 -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="top: 100px;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">输入回复规则</h4>
                </div>
                <form action="{{ route('admin.wechat.auto_reply.store') }}" method="post" class="form-horizontal">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div style="padding: 0 30px;">
                            <div class="form-group">
                                <label for="regex">回复规则</label>
                                <input type="text" class="form-control" name='regex' placeholder="请输入触发规则">
                            </div>
                            <div class="form-group">
                                <label for="reply">回复内容</label>
                                <textarea id="reply" name="reply" style="height: 100px;" placeholder="请输入回复内容">
                                    <p></p>
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" >保存规则</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="alterReply" tabindex="-1" role="dialog" aria-labelledby="alterReplyLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="alterReplyLabel">修改回复规则</h4>
                </div>
                <form class="form form-horizontal" method="post" action="{{ route('admin.wechat.auto_reply.edit') }}">
                    {{ csrf_field() }}
                    <input type="text" id="auto_reply_id" name="auto_reply_id" class="hidden">
                    <div class="modal-body">
                        <div style="padding:0 30px;">
                            <div class="form-group">
                                <label for="alter_regex" class="control-label">回复规则</label>
                                <input type="text" class="form-control" id="alter_regex" name="alter_regex" />
                            </div>
                            <div class="form-group">
                                <label for="alter_reply" class="control-label">回复内容</label>
                                <textarea class="form-control" id="alter_reply" name="alter_reply" style="height: 100px;">

                            </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-primary">保存规则</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        function showModal(modal_id,auto_reply_id,regex) {
            if(modal_id == 'myModal'){
                $("#myModal").modal('show');
            }else if(modal_id == 'alterReply'){
                $("#alterReply").modal('show');
                $("#alter_regex").val(regex);
                $("#auto_reply_id").val(auto_reply_id);
            }
        }

        $('#myModal').on('shown.bs.modal', function () {
            console.log('hi');
        });

        $(function () {
            var editor = new wangEditor('reply');
            editor.config.menus = [
                'link',
                'unlink'
            ];
            editor.create();
        });

        $(function () {
            var editor = new wangEditor('alter_reply');
            editor.config.menus = [
                'link',
                'unlink'
            ];
            editor.create();
        });

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