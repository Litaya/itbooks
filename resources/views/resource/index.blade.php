@extends('layouts.frame')

@section('title', '资源列表')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="table"> 
                <thead>
                    <tr>
                        <th style="width: 20%">资源名称</th>
                        <th>资源类型</th>
                        <th>上传者</th>
                        <th>上传日期</th>
                        <th style="width: 25%">资源描述</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($resources as $res)
                <!-- IF user has proficient privilege to see $res -->
                    <tr>
                        <td><a href="{{route('resource.show', $res->id)}}">
                        {{$res->title}}</a></td>
                        <td>{{$res->type}}</td>
                        <td>{{$res->ownerUser->username}}
                        <td>{{date('Y-m-d', strtotime($res->created_at))}}</td>
                        <td>
                            {{mb_strlen($res->description)>25?
                                mb_substr($res->description, 0, 22)."...":
                                $res->description}}
                        </td>
                    </tr>
                <!-- END IF user has proficient privilege to see $res -->
                @endforeach
                </tbody>
                </table>
            </div>
        </div>
        {{$resources->links()}}
    </div>

@endsection