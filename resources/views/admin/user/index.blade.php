@extends('admin.layouts.frame')

@section('content')
    <div class="panel panel-primary">
        <div class="panel-heading"><span style="font-size: large">全部用户</span></div>
        <div class="panel-body">
            <table class="table table-default table-hover" >
                <tr>
                    <th style="border-top:none">id</th>
                    <th style="border-top:none">用户名</th>
                    <th style="border-top:none">性别</th>
                    <th style="border-top:none">邮箱</th>
                    <th style="border-top:none">邮箱是否验证</th>
                    <th style="border-top:none">是否已关注公众号</th>
                    <th style="border-top:none">来源</th>
                </tr>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->gender==0?'未知':($user->gender==1?'男':'女')}}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->email_status==1?'已验证':'未验证' }}</td>
                        <td>{{ $user->subscribed == 1? '已关注': '未关注' }}</td>
                        <td>{{ $user->source }}</td>
                    </tr>
                @endforeach
            </table>
            {{ $users->links() }}
        </div>
    </div>

@stop