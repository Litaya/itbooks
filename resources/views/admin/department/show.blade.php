@extends('admin.layouts.frame')

@section('content')

    <h3>{{ $department->code }}-{{ $department->name }}
        <a href="javascript:void(0)" style="font-size:18px;"><small data-toggle="modal" data-target="#edditDepartment" style="color:#7098DA"><i class="fa fa-pencil"></i> 修改分社信息 </small></a>
        &nbsp;&nbsp;<a href="javascript:void(0)" style="font-size:18px;"><small data-toggle="modal" data-target="#addOffice" style="color:#7098DA"><i class="fa fa-plus"></i> 添加编辑室 </small></a>
    </h3>
    <hr>
    @foreach($offices as $office)
        @if($office->type == 2)
            <button class="btn btn-primary" style="margin:5px;" data-toggle="modal" data-target="#eddit-office-{{ $office->id }}" >
                <span>{{ $office->code }}-{{$office->name}}</span>
            </button>


            <div class="modal fade bs-example-modal-sm" id="eddit-office-{{ $office->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteOfficeLable" style="margin-top: 200px">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="deleteOfficeLable">编辑室{{ $office->code }}-{{ $office->name }}</h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('admin.office.delete',['department_id'=>$department->id]) }}" method="post">
                                {{ csrf_field() }}
                                <input type="text" name="office-id" value="{{ $office->id }}" hidden>
                                <input type="submit" class="btn btn-danger" value="删除"/>
                                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        @endif
    @endforeach
    <br>
    @foreach($offices as $office)
        @if($office->type == 3)
            <button class="btn btn-default" style="margin:5px;" data-toggle="modal" data-target="#eddit-office-{{ $office->id }}" >
                <span>{{ $office->code }}-{{$office->name}}</span>
            </button>


            <div class="modal fade bs-example-modal-sm" id="eddit-office-{{ $office->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteOfficeLable" style="margin-top: 200px">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="deleteOfficeLable">编辑室{{ $office->code }}-{{ $office->name }}</h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('admin.office.delete',['department_id'=>$department->id]) }}" method="post">
                                {{ csrf_field() }}
                                <input type="text" name="office-id" value="{{ $office->id }}" hidden>
                                <input type="submit" class="btn btn-danger" value="删除"/>
                                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    {{--<!-- Button trigger modal -->--}}
    {{--<button type="button" class="btn btn-primary btn-lg" >--}}
    {{--Launch demo modal--}}
    {{--</button>--}}

    {{--添加编辑室--}}
    <div class="modal fade " id="addOffice" tabindex="-1" role="dialog" aria-labelledby="addOfficeLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addOfficeLabel">添加编辑室</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.department.create') }}" method="post" class="form-horizontal" >
                        {{ csrf_field() }}
                        <input type="text" name="department-id" value="{{ $department->id }}" hidden>
                        <div class="form-group">
                            <label for="office-code" class="col-lg-2 control-label">编号</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="office-code" name="office-code" placeholder="请输入编辑室号码">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="office-name" class="col-lg-2 control-label">名称</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="office-name" name="office-name" placeholder="请输入编辑室名称">
                            </div>
                        </div>
                        {{----}}
                        {{--<div class="form-group">--}}
                            {{--<label class="col-lg-2 control-label">类型</label>--}}
                            {{--<div class="col-lg-10" >--}}
                                {{--<div class="btn-group" data-toggle="buttons">--}}
                                    {{--<label class="btn btn-primary active">--}}
                                        {{--<input type="radio" name="department-type" id="option-department" autocomplete="off" checked value="1"> 分社--}}
                                    {{--</label>--}}
                                    {{--<label class="btn btn-primary">--}}
                                        {{--<input type="radio" name="department-type" id="option-organization" autocomplete="off" value="2"> 事业部--}}
                                    {{--</label>--}}
                                    {{--<label class="btn btn-primary">--}}
                                        {{--<input type="radio" name="department-type" id="option-office" autocomplete="off" value="3"> 编辑室--}}
                                    {{--</label>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        编号 &nbsp;
                        @foreach($offices as $office)
                            @if($office->type == 3)
                                {{ $office->code }} &nbsp;
                            @endif
                        @endforeach
                        已被使用
                        <hr>
                        <div class="form-group">
                            <div class="col-lg-offset-9 col-lg-3">
                                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                <input type="submit" class="btn btn-primary" value="添加">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{--修改分社信息--}}
    <!-- Modal -->
    <div class="modal fade " id="edditDepartment" tabindex="-1" role="dialog" aria-labelledby="addOfficeLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addOfficeLabel">编辑分社信息</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.department.update',['department-id'=>$department->id])}}" method="post" class="form-horizontal" >
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="department-code" class="col-lg-2 control-label">编号</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="department-code" name="department-code" value="{{ $department->code }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="department-name" class="col-lg-2 control-label">名称</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="department-name" name="department-name" value="{{ $department->name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-8 col-lg-4">
                                <input type="submit" class="btn btn-primary" value="提交修改"/>
                                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#addOffice').modal(options)
    </script>
@stop