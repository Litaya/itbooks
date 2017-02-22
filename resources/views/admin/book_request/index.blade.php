@extends('admin.layouts.frame')

@section('title', '样书申请管理')

@section('content')

    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <table class="table"> 
                <thead>
                    <tr>
                        <th>书名</th>
                        <th>状态</th>
                        <th>留言</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($bookreqs as $bookreq)
                    <form id="remove-form-{{$bookreq->id}}" action="{{ route('bookreq.destroy', $bookreq->id) }}" 
                        method="delete" style="display: none;">
                        <input type="hidden" name="method" value="DELETE">
                        {{ csrf_field() }}
                    </form>
                    <tr>
                        <td>{{$bookreq->book_id}}</td>
                        <td>{{$bookreq->status==0?"待审核":($bookreq->status==1?"通过":"未通过")}}</td>
                        <td>{{$bookreq->message}}</td>
                        <td>
                        <div class="row">
                            <div class="col-md-2">
                                <a href="{{route('admin.bookreq.show', $bookreq->id)}}">
                                    <button class="btn btn-primary btn-xs">详情</button>
                                </a>
                            </div>
                            <!-- IF HAS PASS PERMISSION -->
                            <div class="col-md-2">
                                {!! Form::open(['route'=>['admin.bookreq.pass', $bookreq->id], 'method'=>'POST']) !!}
                                {!! Form::submit('通过', ['class'=>'btn btn-success btn-xs']) !!}
                                {!! Form::close() !!}
                            </div>
                            <!-- END IF HAS PASS PERMISSION -->
                            <!-- IF HAS DELETE PERMISSION -->
                            <div class="col-md-2">
                                {!! Form::open(['route'=>['bookreq.destroy', $bookreq->id], 'method'=>'DELETE']) !!}
                                {!! Form::submit('删除', ['class'=>'btn btn-danger btn-xs']) !!}
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