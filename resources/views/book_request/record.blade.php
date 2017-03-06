@extends('layouts.frame')

@section('title', '样书申请')

@section('content')
    <div class="container">
        <p><small>您可在个人中心上传相关书籍的学校订书单,审核通过后相关申请不扣总的申请次数</small></p>
        <div class="row">
            <div class="col-md-12">
                <table class="table"> 
                <thead>
                    <tr>
                        <th style="width: 35%">图书名</th>
                        <th>申请状态</th>
                        <th>发起日期</th>
                        <th style="width: 25%"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach(Auth::user()->bookRequests as $bookreq)
                    <form id="remove-form-{{$bookreq->id}}" action="{{ route('bookreq.destroy', $bookreq->id) }}" 
                        method="delete" style="display: none;">
                        <input type="hidden" name="method" value="DELETE">
                        {{ csrf_field() }}
                    </form>
                    <tr>
                        <td>{{$bookreq->book->name}}</td>
                        <td>{{$bookreq->status==0?"审核中":($bookreq->status==1?"通过":"未通过")}}</td>
                        <td>{{date('Y-m-d', strtotime($bookreq->created_at))}}</td>
                        <td>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{route('bookreq.show', $bookreq->id)}}">
                                <button class="btn btn-default btn-xs">详情</button></a>
                            </div>
                            <!-- IF HAS DELETE PERMISSION -->
                            <div class="col-md-6">
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

@endsection