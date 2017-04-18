@extends('admin.wechat.layout')

@section('wechat-content')
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
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">
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
                                <button class="btn btn-success btn-xs">修改</button> &nbsp;&nbsp;
                                <button class="btn btn-danger btn-xs" onclick="removeAutoReply('{{ route('admin.wechat.auto_reply.destroy',$wechat_auto_reply->id) }}')">删除</button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>
    </div>

    <!-- Modal -->
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

    <div class="row">
    </div>
    <script type="text/javascript" src="/editors/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/editors/js/wangEditor.min.js"></script>
    <script type="text/javascript">
        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').focus()
        });

        $(function () {
            var editor = new wangEditor('reply');
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