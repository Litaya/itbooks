@extends('admin.layouts.frame')

@section('title', '用户订购反馈管理')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>用户订购反馈记录</h4>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th width="8%">用户</th>
                                <th width="15%">书名</th>
                                <th width="8%">ISBN</th>
                                <th width="8%">所属分社</th>
                                <th width="5%">数量</th>
                                <th width="8%">订购日期</th>
                                <th width="13%">申请时间</th>
                                <th width="15%">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order_fbs as $fb)
                                <td>{{ $fb->user_realname }}</td>
                                <td>{{ $fb->book->name }}</td>
                                <td>{{ $fb->book_isbn }}</td>
                                <td>{{ $fb->bookDepartmentName() }}</td>
                                <td>{{ $fb->order_count }}</td>
                                <td>{{ date("Y-m-d",time($fb->order_time)) }}</td>
                                <td>{{ date("Y-m-d H:i",time($fb->created_at)) }}</td>
                                <td>
                                    <div class="row">
                                        <a href="{{ route('admin.order_fb.show',['id'=>$fb->id]) }}"><button class="btn btn-sm btn-primary">查看</button></a>
                                        <button class="btn btn-sm btn-danger">拒绝</button>
                                        <button class="btn btn-sm btn-success">同意</button>
                                    </div>
                                </td>
                            @endforeach
                            </tbody>
                        </table>

                        <div>
                            {{ $order_fbs->appends(Input::except('page'))->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
