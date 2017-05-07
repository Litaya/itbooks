@extends('layouts.frame')

@section('title','教师信息')

@section('content')
    <table class="table table-bordered">
        <tr>
            <td>姓名</td>
            <td>{{ $cert->realname }}</td>
        </tr>
        <tr>
            <td>状态</td>
            <td>{{ $cert->status?"已认证":"等待认证" }}</td>
        </tr>
        <tr>
            <td>课程1</td>
            <td>{{ $cert->json_content->course_name_1 }}/{{ $cert->json_content->number_stud_1 }}</td>
        </tr>
        @if($cert->json_content->course_name_2!="")
            <tr>
                <td>课程2</td>
                <td>{{ $cert->json_content->course_name_2 }}/{{ $cert->json_content->number_stud_2 }}</td>
            </tr>
        @endif
        @if($cert->json_content->course_name_3!="")
            <tr>
                <td>课程3</td>
                <td>{{ $cert->json_content->course_name_3 }}/{{ $cert->json_content->number_stud_3 }}</td>
            </tr>
        @endif
    </table>
    <p>您今年的样书申请额度还有{{$user->json_content->teacher->book_limit}}本</p>
    <img src="{{ "/image/".$cert->img_upload }}" alt="教师证" style="width: 100%;"><br>
@stop