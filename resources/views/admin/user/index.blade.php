@extends('admin.layouts.frame')

@section('content')
    <div class="col-lg-12">
        {{--<button class="btn btn-primary" style="margin:5px;" data-toggle="modal" data-target="#addAdminUser" >--}}
            {{--<span> <i class="fa fa-plus push"></i>添加管理员 </span>--}}
        {{--</button>--}}
        {{--<hr>--}}

        {{-- create admin user --}}
        <div class="modal fade" id="addAdminUser" tabindex="-1" role="dialog" aria-labelledby="deleteOfficeLable" style="margin-top: 100px">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="deleteOfficeLable">添加管理员</h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.user.create') }}" class="form-horizontal">
                            <div class="form-group">
                                <label for="username" class="col-lg-2 control-label">用户名</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" id="username" name="username" placeholder="请输入用户名">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">类型</label>
                                <div class="col-lg-10" >
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-primary active">
                                            <input type="radio" name="admin-type" id="option-department" autocomplete="off" checked value="DEPARTMENT_ADMIN"> 部门管理员
                                        </label>
                                        <label class="btn btn-primary">
                                            <input type="radio" name="admin-type" id="option-organization" autocomplete="off" value="REPRESENTATIVE"> 院校代表
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">权限区域</label>
                                <div class="col-lg-10">
                                    多选框区
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-12">
        <div class="col-lg-8">
            <div class="panel panel-primary">
                <div class="panel-heading">管理员</div>
                <div class="panel-body">
                    <table class="table table-default table-hover">
                        <tr>
                            <th>id</th>
                            <th>用户名</th>
                            <th>身份</th>
                            <th>权限</th>
                        </tr>
                        @foreach($admins as $admin)
                            <tr>
                                <td>{{ $admin->id }}</td>
                                <td>{{ $admin->username }}</td>
                                <td>{{ $admin->certificate_as }}</td>
                                <td>{{ $admin->permission_string }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading"><span>全部用户</span></div>
                <div class="panel-body">
                    <table class="table table-default table-hover" >
                        <tr>
                            <th style="border-top:none">id</th>
                            <th style="border-top:none">用户名</th>
                            <th style="border-top:none">来源</th>
                        </tr>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->source }}</td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@stop