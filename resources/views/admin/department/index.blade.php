@extends('admin.layouts.frame')

@section('content')

    {{--<form action="#" >--}}
    {{--<div class="form-group">--}}
    {{--<input type="text" class="form-control" id="inputEmail3" placeholder="输入编辑室名或编号查找编辑室">--}}
    {{--</div>--}}
    {{--</form>--}}

    @if(!empty($departments))
        <div class="container">
            <h3>我的分社</h3>
            <hr>
            @foreach( $departments as $department)
                <div class="col-lg-4 col-md-4 col-xs-4 department-module">
                    <a href=" {{ route('admin.department.show',['department_code'=>$department->code]) }}">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <p>
                                    {{ $department->code }}
                                </p>
                                <p>
                                    {{ $department->name }}
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    @if(!empty($orgnizations))
        <div class="container">
            <h3>我的事业部</h3>
            <hr>
            @foreach($orgnizations as $orgnization)
                <button class="btn btn-primary" style="margin:5px;" data-toggle="modal" data-target="#eddit-office-{{ $orgnization->id }}" >
                    <span>{{ $orgnization->code }}-{{$orgnization->name}}</span>
                </button>


                <div class="modal fade bs-example-modal-sm" id="eddit-office-{{ $orgnization->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteOfficeLable" style="margin-top: 200px">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="deleteOfficeLable">{{ $orgnization->code }}-{{ $orgnization->name }}</h4>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.office.delete',['department_code'=>$department->code]) }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="text" name="office-id" value="{{ $orgnization->id }}" hidden>
                                    <input type="submit" class="btn btn-danger" value="删除"/>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if(!empty($offices))
        <div class="container">
            <h3>我的编辑室</h3>
            <hr>
            @foreach( $offices as $office)
                <button class="btn btn-default" style="margin:5px;" data-toggle="modal" data-target="#eddit-office-{{ $office->id }}" >
                    <span>{{ $office->code }}-{{$office->name}}</span>
                </button>


                <div class="modal fade bs-example-modal-sm" id="eddit-office-{{ $office->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteOfficeLable" style="margin-top: 200px">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="deleteOfficeLable">{{ $office->code }}-{{ $office->name }}</h4>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.office.delete',['department_code'=>$department->code]) }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="text" name="office-id" value="{{ $office->id }}" hidden>
                                    <input type="submit" class="btn btn-danger" value="删除"/>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@stop