@extends('admin.layouts.frame')

@section('content')

    {{--<form action="#" >--}}
        {{--<div class="form-group">--}}
            {{--<input type="text" class="form-control" id="inputEmail3" placeholder="输入编辑室名或编号查找编辑室">--}}
        {{--</div>--}}
    {{--</form>--}}

    <h3>我的分社</h3>
    <hr>
    @foreach( $departments as $department)
        <div class="col-lg-4 department-module">
            <a href=" {{ route('admin.department.show',['id'=>$department->id]) }}">
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
@stop