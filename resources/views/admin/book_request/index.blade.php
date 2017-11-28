@extends('admin.layouts.frame')

@section('title', '样书申请管理')

@section('content')

    <div class="container">
        <div class="row">
            <!-- SEARCH BAR -->
            <div class="col-md-4">
            {!! Form::open(["route"=>"admin.bookreq.index", "method"=>"GET"]) !!}
            {{ Form::text("search", Input::get('search'), ["placeholder"=>"ISBN、书名、用户名..."]) }}
            {{ Form::select("category", [""=>"-类别-", "handled"=>"已处理", "unhandled"=>"未处理"], Input::get('category')) }}
            {{ Form::submit("搜索") }}
            {!! Form::close() !!}
            </div>

            @if(in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"]))
            <div class="col-md-7 col-md-offset-1">
                <a href="{{route('admin.bookreq.export.bookreq')}}"><button class="btn btn-sm btn-primary move-right">导出全部样书申请单</button></a>
                <a href="{{route('admin.bookreq.export.book')}}"><button class="btn btn-sm btn-warning move-right">导出库房发书单</button></a>
                <a href="{{route('admin.bookreq.export.packaging')}}"><button class="btn btn-sm btn-success move-right">导出快递打印单</button></a>
                <a href="{{route('admin.bookreq.export.invoice') }}"><button class="btn btn-sm btn-danger move-right">导出发行单</button></a>
                <button class="btn btn-sm btn-primary move-right" data-toggle="modal" data-target="#import-express" >导入发行单</button>


                <div class="modal fade bs-example-modal-sm" id="import-express" tabindex="-1" role="dialog" aria-labelledby="deleteOfficeLable" style="margin-top: 200px">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="deleteOfficeLable">导入发行单</h4>
                            </div>
                            <script>
                                function submit(){
                                    var form = new FormData(document.getElementById("import_express_form"));
                                    $("#express_submit").value("正在提交中...").setAttribute("disabled","disabled");
                                    $.ajax({
                                        type: 'POST',
                                        url: '/admin/bookreq/importexpress',
                                        data: form,
                                        processData:false,
                                        contentType:false,
                                        success: function(){
                                            $("#express_submit").value("提交").setAttribute("disabled","");
                                            window.location.reload();
                                        },
                                        error: function(xhr, type){
                                            alert('请手动刷新页面！');
                                        }
                                    });
                                }
                                $(function () {
                                    $("#import_express_form").submit(function (e) {
                                        submit();
                                        return false;
                                    });
                                })
                            </script>
                            <div class="modal-body">
                                {!! Form::open(["route"=>"admin.bookreq.import_express","id"=>"import_express_form", "method"=>"post", "files"=>true]) !!}
                                {{ Form::file("express_file", ["class"=>"form-control form-spacing-top"])}}
                                {{ Form::submit("导入", ["class"=>"btn btn-primary form-spacing-top","id"=>"express_submit"])}}
                                <button type="button" class="btn btn-default form-spacing-top" data-dismiss="modal">取消</button>
                                {!! Form::close() !!}
                                <hr>
                                <div>
                                    <span style="color:red;">注意事项:</span><br>
                                    1. 表头的标准格式:快递单号、状态、ISBN、定价、数量、书名、姓名、电话、地址 <br>
                                    2. 每个excel文件下只能有一张工作表 <br>
                                    3. 请保证isbn与用户真实名字的准确性 <br>
                                    4. 同一份文件可提交多次
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- TODO 导出发货单 admin.bookreq.export.invoice -->
            </div>
            @endif
            <!-- END SEARCH BAR -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                <thead>
                    <tr>
                        <th width="6%">用户</th>
                        <th width="15%">书名</th>
                        <th width="8%">ISBN</th>
                        <!-- th width="15%">作者</th -->
                        <th width="6%">编辑</th>
                        <th width="8%">所属分社</th>
                        <th width="8%">申请时间</th>
                        <th width="10%">收货人</th>
                        <th width="10%">状态</th>
                        <th style="width: 18%">操作</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($bookreqs as $bookreq)
                    <tr>
                        <!-- USER INFO -->
                        @if(!empty($bookreq->user))
                            <td>{{$bookreq->user->username}}</td>
                        @endif

                        <!-- BOOK INFO -->
                        @if(!empty($bookreq->book))
                            <td>{{$bookreq->book->name}}</td>
                            <td>{{substr($bookreq->book->isbn, strlen($bookreq->book->isbn) - 6)}}</td>
                            <!-- 不需要显示作者了
                            @if(preg_match("/[a-z].*/", $bookreq->book->authors))
                                <!-- ENGLISH TITLE ->
                                <td>{{strlen($bookreq->book->authors) > 30 ? substr($bookreq->book->authors, 0, 27) . "..." : $bookreq->book->authors}}</td>
                            @else
                                <!-- CHINESE TITLE ->
                                <td>{{mb_strlen($bookreq->book->authors) > 10 ? mb_substr($bookreq->book->authors, 0, 7) . "..." : $bookreq->book->authors}}</td>
                            @endif
                            -->
                            <td>{{$bookreq->book->editor_name}}</td>
                        @else
                            <td><span style="color: #AAA;"><small>不存在</small></span></td>
                            <td><span style="color: #AAA;"><small>不存在</small></span></td>
                            <!-- td><span style="color: #AAA;"><small>不存在</small></span></td -->
                            <td><span style="color: #AAA;"><small>不存在</small></span></td>
                        @endif

                        @if(!empty($bookreq->book) and !empty($bookreq->book->department))
                            <td>{{$bookreq->book->department->name}}</td>
                        @else
                            <td><span style="color: #AAA;"><small>不存在</small></span></td>
                        @endif

                        <td>{{$bookreq->created_at}}</td>
                        <td>{{$bookreq->receiver}}</td>

                        @if($bookreq->status==0)
                            <td>待审核
                                @if(!empty($bookreq->handler))
                                <b>[{{$bookreq->handler->username}}]</b>
                                @endif
                                @if(!empty($j = json_decode($bookreq->message)))
                                    @if(!empty($j->remarks))
                                    <br>(有备注)
                                    @endif
                                @endif
                            </td>
                        @elseif($bookreq->status==1)
                            <td>通过
                                @if(!empty($bookreq->handler))
                                <b>[{{$bookreq->handler->username}}]</b>
                                @endif
                            </td>
                        @elseif($bookreq->status==2)
                            <td>未通过
                                @if(!empty($bookreq->handler))
                                <b>[{{$bookreq->handler->username}}]</b>
                                @endif
                            </td>
                        @endif

                        <td>
                        <div class="row">

                            <!-- BEGIN ROLE CHECK FOR REQUEST PROCESS-->
                            @if(in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"]))
                                @if($bookreq->status==0)
                                    <div class="col-xs-5 col-md-2">
                                    <button type="button"
                                            class="btn btn-success btn-xs"
                                            data-toggle="modal"
                                            data-target="#order-modal-{{$bookreq->id}}">
                                            通过
                                    </button>
                                    </div>
                                    <div class="col-xs-5 col-md-2">
                                    <button type="button"
                                            class="btn btn-danger btn-xs"
                                            data-toggle="modal"
                                            data-target="#reject-modal-{{$bookreq->id}}">
                                            拒绝
                                    </button>
                                    </div>
                                @endif

                                @if(!empty($bookreq->book) and !empty($bookreq->book->department))
                                <div class="col-xs-5 col-md-2">
                                    <a href="{{route('admin.bookreq.show', $bookreq->id)}}">
                                        <button class="btn btn-primary btn-xs">详情</button>
                                    </a>
                                </div>
                                @endif

                                <div class="col-xs-5 col-md-2">
                                    {!! Form::open(['route'=>['admin.bookreq.destroy', $bookreq->id], 'method'=>'DELETE']) !!}
                                    <input type="hidden" name="category" value="{{Input::get('category')}}">
                                    <input type="hidden" name="search" value="{{Input::get('search')}}">
                                    <input type="hidden" name="page" value="{{Input::get('page')}}">
                                    {!! Form::submit('删除', ['class'=>'btn btn-default btn-xs']) !!}
                                    {!! Form::close() !!}
                                </div>
                            @endif
                            @if(in_array(PM::getAdminRole(), ["REPRESENTATIVE"]))
                                @if(!empty($bookreq->book) and !empty($bookreq->book->department))
                                <div class="col-xs-5 col-md-2">
                                    <a href="{{route('admin.bookreq.show', $bookreq->id)}}">
                                        <button class="btn btn-primary btn-xs">详情</button>
                                    </a>
                                </div>
                                @endif
                            @endif
                            <!-- END ROLE CHECK FOR REQUEST PROCESS -->

                        </div>
                        </td>
                    </tr>
                    <!-- BEGIN ROLE CHECK FOR MODAL PAGE -->
                    @if(in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"]) and $bookreq->status==0)
                        <div class="modal fade" id="order-modal-{{$bookreq->id}}"
                            tabindex="-1" role="dialog"
                            aria-labelledby="order-modal-label">
                            <div class="modal-dialog" role="dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="order-modal-label">输入订单号</h4>
                                    </div>
                                    <div class="modal-body">
                                    {!! Form::open(["route"=>["admin.bookreq.passorder", $bookreq->id], "method"=>"POST"]) !!}
                                    {{ Form::label("order_number", "订单号:") }}
                                    {{ Form::text("order_number", null, ["class"=>"form-control"]) }}
                                    <input type="hidden" name="category" value="{{Input::get('category')}}">
                                    <input type="hidden" name="search" value="{{Input::get('search')}}">
                                    <input type="hidden" name="page" value="{{Input::get('page')}}">
                                    {{ Form::submit('确认', ['class'=>'btn btn-success btn-lg btn-block', 'style'=>"margin-top: 10px"]) }}
                                    {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="reject-modal-{{$bookreq->id}}"
                            tabindex="-1" role="dialog"
                            aria-labelledby="reject-modal-label">
                            <div class="modal-dialog" role="dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="reject-modal-label">拒绝申请</h4>
                                    </div>
                                    <div class="modal-body">

                                    {!! Form::open(["route"=>["admin.bookreq.reject", $bookreq->id], "method"=>"POST"]) !!}
                                    {{ Form::label("message", "拒绝理由:") }}
                                    {{ Form::textarea("message", null, ["class"=>"form-control"]) }}
                                    <input type="hidden" name="category" value="{{Input::get('category')}}">
                                    <input type="hidden" name="search" value="{{Input::get('search')}}">
                                    <input type="hidden" name="page" value="{{Input::get('page')}}">
                                    {{ Form::submit('确认', ['class'=>'btn btn-danger btn-lg btn-block', 'style'=>"margin-top: 10px"]) }}
                                    {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- END ROLE CHECK FOR MODAL PAGE -->
                @endforeach
                </tbody>
                </table>
                <div>
                {!! $bookreqs->appends(Input::except('page'))->links() !!}
                </div>
            </div>
        </div>
        </div>
    </div>


@endsection
