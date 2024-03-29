@extends('admin.layouts.frame')

@section('title', '样书申请管理')

@section('content')

    <div class="container">
        <div class="row" style="margin-bottom: 10px;">
            <!-- SEARCH BAR -->
            <div class="col-lg-12 col-md-12 col-xs-12">
                {!! Form::open(["route"=>"admin.bookreq.index", "class"=>"form form-inline", "method"=>"GET"]) !!}
                <div class="form-group">
                    {{ Form::text("search", Input::get('search'), ["placeholder"=>"ISBN、书名、用户名...","class"=>"form-control"]) }}

                </div>
            {{ Form::select("category", ["class"=>"form-control",""=>"-类别-", "handled"=>"已处理", "unhandled"=>"未处理"], Input::get('category')) }}
            {{ Form::submit("搜索",["class"=>"btn btn-default"]) }}
            {!! Form::close() !!}
            <!-- END SEARCH BAR -->
            </div>
        </div>
        @if(in_array(PM::getAdminRole(), ["SUPERADMIN", "DEPTADMIN"]))
            @include('admin.book_request.buttons')
        @endif

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


@endsection
