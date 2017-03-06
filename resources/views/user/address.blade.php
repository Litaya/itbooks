@extends('layouts.frame')

@section('title','邮箱')

@section('content')
    <table class="table table-bordered">
        <tr>
            <td style="min-width: 80px">收件人</td>
            <td>{{ $user->json_content->address->receiver }}</td>
        </tr>
        <tr>
            <td>电话</td>
            <td>{{ $user->json_content->address->phone }}</td>
        </tr>
        <tr>
            <td>详细地址</td>
            <td>{{ $user->json_content->address->location }}</td>
        </tr>
    </table>

@stop