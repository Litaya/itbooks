@extends('admin.layouts.frame')

@section('title', '样书申请管理')

@section('content')

    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <table class="table"> 
                <thead>
                    <tr>
                        <th style="width: 35%">书名</th>
                        <th>状态</th>
                        <th>留言</th>
                        <th style="width: 20%"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($bookreqs as $bookreq)
                    <tr>
                        <td>{{$bookreq->book->name}}</td>
                        <td>{{$bookreq->status==0?"待审核":($bookreq->status==1?"通过":"未通过")}}</td>
                        <td>{{mb_strlen($bookreq->message)>30?mb_substr($bookreq->message, 0, 27)."...":$bookreq->message}}</td>
                        <td>
                        <div class="row">
                            <div class="col-md-2">
                                <a href="{{route('admin.bookreq.show', $bookreq->id)}}">
                                    <button class="btn btn-primary btn-xs">详情</button>
                                </a>
                            </div>
                            <!-- IF HAS PASS PERMISSION -->
                            @if($bookreq->status==0)
                            <div class="col-md-2">
                                {!! Form::open(['route'=>['admin.bookreq.pass', $bookreq->id], 'method'=>'POST']) !!}
                                {!! Form::submit('通过', ['class'=>'btn btn-success btn-xs']) !!}
                                {!! Form::close() !!}
                            </div>
                            <!-- END IF HAS PASS PERMISSION -->
                            <div class="col-md-2">
                                {!! Form::open(['route'=>['admin.bookreq.reject', $bookreq->id], 'method'=>'POST']) !!}
                                {!! Form::submit('拒绝', ['class'=>'btn btn-danger btn-xs']) !!}
                                {!! Form::close() !!}
                            </div>
                            @endif
                            <!-- IF HAS DELETE PERMISSION -->
                            <div class="col-md-2">
                                {!! Form::open(['route'=>['admin.bookreq.destroy', $bookreq->id], 'method'=>'DELETE']) !!}
                                {!! Form::submit('删除', ['class'=>'btn btn-default btn-xs']) !!}
                                {!! Form::close() !!}
                            </div>
                            <!-- END IF HAS DELETE PERMISSION -->
                        </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>

@endsection