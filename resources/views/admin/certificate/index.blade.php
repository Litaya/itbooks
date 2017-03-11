@extends('admin.layouts.frame')

@section('title', '用户认证管理')

@section('content')

    <div class="container">
        <div class="row">
            <!-- SEARCH BAR -->
            <div class="col-md-8"> 
            {!! Form::open(["route"=>"admin.cert.index", "method"=>"GET"]) !!}
            {{ Form::text("search", null, ["placeholder"=>"搜索指定用户"]) }}
            {{ Form::submit("搜索") }}
            {!! Form::close() !!}
            </div>
            <!-- END SEARCH BAR -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table"> 
                <thead>
                    <tr>
                        <th>用户名</th>
                        <th>姓名</th>
                        <th>工作单位</th>
                        <th>申请角色</th>
                        <th>状态</th>
                        <th width="30%"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($certs as $cert)
                    <form id="remove-form-{{$cert->id}}" action="{{ route('admin.cert.destroy', $cert->id) }}" 
                        method="POST" style="display: none;">
                        <input type="hidden" name="_method" value="DELETE">
                        {{ csrf_field() }}
                    </form>
                    <form id="deprive-form-{{$cert->id}}" action="{{ route('admin.cert.deprive', $cert->id) }}" 
                        method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                    <tr>
                        <td>{{$cert->user->username}}</td>
                        <td>{{$cert->realname}}</td>
                        <td>{{$cert->workplace}}</td>
                        <td>{{$cert->cert_name=="TEACHER"?"教师":"作者"}}</td>
                        @if($cert->status==1)
                        <td>通过 <a href="javascript:$('#deprive-form-{{$cert->id}}').submit()">收回</a>
                        </td>
                        @else
                        <td>{{$cert->status==0?"待审核":($cert->status==2?"未通过":"已取消")}}</td>
                        @endif
                        <td>
                        <div class="row">
                            <div class="col-xs-2">
                                <a href="{{route('admin.cert.show', $cert->id)}}">
                                    <button class="btn btn-primary btn-xs">详情</button>
                                </a>
                            </div>
                            <!-- IF HAS PASS PERMISSION -->
                            @if($cert->status == 0)
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
                            @endif
                            <!-- END IF HAS PASS PERMISSION -->
                            <!-- IF HAS DELETE PERMISSION -->
                            <div class="col-xs-2">
                                <button class="btn btn-danger btn-xs" onclick="cert_destory({{$cert->id}});">删除</button>
                                <!-- {!! Form::open(['route'=>['admin.cert.destroy', $cert->id], 'method'=>'DELETE']) !!}
                                {!! Form::submit('删除', ['class'=>'btn btn-danger btn-xs']) !!}
                                {!! Form::close() !!} -->
                            </div>
                            <!-- END IF HAS DELETE PERMISSION -->
                        </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                </table>
                <div>
                {!! $certs->appends(Input::except('page'))->links() !!}
                </div>
            </div>
        </div>
        </div>
    </div>

<script>
// 添加删除保护
function cert_destory(id){
    formname = 'remove-form-'+id.toString();
    if(window.confirm("确定要删除此条申请记录吗？（用户的当前身份不受影响）")){
        document.getElementById(formname).submit();
    }
}

</script>

@endsection