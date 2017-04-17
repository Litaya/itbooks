@extends('admin.layouts.frame')

@section('title', '样书申请管理')

@section('content')

    <div class="container">
        <div class="row">
            <!-- SEARCH BAR -->
            <div class="col-md-4"> 
            {!! Form::open(["route"=>"admin.bookreq.index", "method"=>"GET"]) !!}
            {{ Form::text("search", null, ["placeholder"=>"ISBN、书名、用户名..."]) }}
            {{ Form::submit("搜索") }}
            {!! Form::close() !!}
            </div>
            
            @if(in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"]))
            <div class="col-md-4 col-md-offset-4">
                <a href="{{route('admin.bookreq.export.book')}}"><button class="btn-default move-right">导出库房发书单</button></a>
                <a href="{{route('admin.bookreq.export.packaging')}}"><button class="btn-default move-right">导出快递打印单</button></a>
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
                        <th>书名</th>
                        <th>ISBN</th>
                        <th>作者</th>
                        <th width="6%">编辑</th>
                        <th width="8%">所属分社</th>
                        <th width="8%">申请时间</th>
                        <th width="6%">状态</th>
                        <th>留言</th>
                        <th style="width: 18%">操作</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($bookreqs as $bookreq)
                    <tr>
                        <td>{{$bookreq->user->username}}</td>
                        <td>{{$bookreq->book->name}}</td>
                        <td>{{$bookreq->book->isbn}}</td>
                        <td>{{$bookreq->book->authors}}</td>
                        <td>{{$bookreq->book->editor}}</td>
                        <td>{{$bookreq->book->department->name}}</td>
                        <td>{{$bookreq->created_at}}</td>
                        <td>{{$bookreq->status==0?"待审核":($bookreq->status==1?"通过":"未通过")}}</td>
                        @if(!empty(json_decode($bookreq->message)))
                        <td>{{mb_strlen(json_decode($bookreq->message)->remarks)>30?mb_substr(json_decode($bookreq->message)->remarks, 0, 27)."...":json_decode($bookreq->message)->remarks}}</td>
                        @else
                        <td>无</td>
                        @endif
                        <td>
                        <div class="row">
                            <div class="col-xs-2 col-md-2">
                                <a href="{{route('admin.bookreq.show', $bookreq->id)}}">
                                    <button class="btn btn-primary btn-xs">详情</button>
                                </a>
                            </div>
                            <!-- IF HAS PASS PERMISSION -->
                        @if(in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"]))
                            @if($bookreq->status==0)
                            <div class="col-xs-2 col-md-2">
                                {!! Form::open(['route'=>['admin.bookreq.pass', $bookreq->id], 'method'=>'POST']) !!}
                                {!! Form::submit('通过', ['class'=>'btn btn-success btn-xs']) !!}
                                {!! Form::close() !!}
                            </div>
                            <!-- END IF HAS PASS PERMISSION -->
                            <div class="col-xs-2 col-md-2">
                            <button type="button"
                                    class="btn btn-danger btn-xs"
                                    data-toggle="modal"
                                    data-target="#reject-modal-{{$bookreq->id}}">
                                    拒绝
                            </button>
                            </div>
                            @endif
                            <!-- IF HAS DELETE PERMISSION -->
                            <div class="col-md-2">
                                {!! Form::open(['route'=>['admin.bookreq.destroy', $bookreq->id], 'method'=>'DELETE']) !!}
                                {!! Form::submit('删除', ['class'=>'btn btn-default btn-xs']) !!}
                                {!! Form::close() !!}
                            </div>
                            <!-- END IF HAS DELETE PERMISSION -->
                        @endif
                        </div>
                        </td>
                    </tr>
                    @if(in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"]))
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
                                    {{ Form::submit('确认', ['class'=>'btn btn-danger btn-lg btn-block', 'style'=>"margin-top: 10px"]) }}
                                    {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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