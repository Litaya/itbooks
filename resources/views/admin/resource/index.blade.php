@extends('admin.layouts.frame')

@section('title', '资源列表')

@section('content')
    <div class="container">
        <div class="row">
            <!-- SEARCH BAR -->
            {!! Form::open(["route"=>"admin.resource.index", "method"=>"GET"]) !!}
            <div class="col-md-6">{{ Form::text("search", null, ["placeholder"=>"资源名称、上传者...","class"=>"form-control"]) }}</div>
            <div class="col-md-2">{{ Form::submit("搜索",["class"=>'btn btn-default']) }}</div>
        {!! Form::close() !!}
        <!-- END SEARCH BAR -->
            <div class="col-md-4">
                <a href="{{route('admin.resource.create')}}"><button class="btn btn-default pull-right">创建资源</button></a>
                {{--<a href="{{route('admin.resource.export')}}"><button class="btn btn-default pull-right">导出课件使用记录</button></a>--}}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width: 20%">资源名称</th>
                                <th>资源类型</th>
                                <th>上传者</th>
                                <th>上传日期</th>
                                <th style="width: 20%">资源描述</th>
                                <th style="width: 10%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($resources as $res)
                                <!-- IF user has proficient privilege to see $res -->
                                <tr>
                                    <td>{{$res->title}}</td>
                                    <td>{{$res->type}}</td>
                                    <td>{{$res->ownerUser->username}}
                                    <td>{{date('Y-m-d', strtotime($res->created_at))}}</td>
                                    <td>
                                        {{mb_strlen($res->description)>25?
                                            mb_substr($res->description, 0, 22)."...":
                                            $res->description}}
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <a href="{{route('admin.resource.show', $res->id)}}">
                                                    <button class="btn btn-default btn-xs">详情</button>
                                                </a>
                                            </div>
                                            <!-- IF HAS DELETE PERMISSION -->
                                            <div class="col-md-6">
                                                {!! Form::open(['route'=>['admin.resource.destroy', $res->id], 'method'=>'DELETE']) !!}
                                                {!! Form::submit('删除', ['class'=>'btn btn-danger btn-xs']) !!}
                                                {!! Form::close() !!}
                                            </div>
                                            <!-- END IF HAS DELETE PERMISSION -->
                                        </div>
                                    </td>
                                </tr>
                                <!-- END IF user has proficient privilege to see $res -->
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {!! $resources->appends(Input::except('page'))->links() !!}
    </div>

@endsection