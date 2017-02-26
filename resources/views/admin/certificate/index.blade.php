@extends('admin.layouts.frame')

@section('title', '用户认证管理')

@section('content')

    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <table class="table"> 
                <thead>
                    <tr>
                        <th>用户ID</th>
                        <th>申请角色</th>
                        <th>状态</th>
                        <th width="30%"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($certs as $cert)
                    <form id="remove-form-{{$cert->id}}" action="{{ route('cert.destroy', $cert->id) }}" 
                        method="delete" style="display: none;">
                        <input type="hidden" name="method" value="DELETE">
                        {{ csrf_field() }}
                    </form>
                    <tr>
                        <td>{{$cert->user_id}}</td>
                        <td>{{$cert->cert_name=="TEACHER"?"教师":"作者"}}</td>
                        <td>{{$cert->status==0?"待审核":($cert->status==1?"通过":"未通过")}}</td>
                        <td>
                        <div class="row">
                            <div class="col-xs-2">
                                <a href="{{route('admin.cert.show', $cert->id)}}">
                                    <button class="btn btn-primary btn-xs">详情</button>
                                </a>
                            </div>
                            <!-- IF HAS PASS PERMISSION -->
                            <div class="col-xs-2">
                                {!! Form::open(['route'=>['admin.cert.pass', $cert->id], 'method'=>'POST']) !!}
                                {!! Form::submit('通过', ['class'=>'btn btn-success btn-xs']) !!}
                                {!! Form::close() !!}
                            </div>
                            <div class="col-xs-2">
                                {!! Form::open(['route'=>['admin.cert.reject', $cert->id], 'method'=>'POST']) !!}
                                {!! Form::submit('拒绝', ['class'=>'btn btn-success btn-xs']) !!}
                                {!! Form::close() !!}
                            </div>
                            <!-- END IF HAS PASS PERMISSION -->
                            <!-- IF HAS DELETE PERMISSION -->
                            <div class="col-xs-2">
                                {!! Form::open(['route'=>['cert.destroy', $cert->id], 'method'=>'DELETE']) !!}
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