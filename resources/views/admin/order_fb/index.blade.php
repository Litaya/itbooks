@extends('admin.layouts.frame')

@section('title', '样书申请管理')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th width="5%">用户</th>
                        <th width="15%">书名</th>
                        <th width="8%">ISBN</th>
                        <th width="8%">所属分社</th>
                        <th width="15%">订购数量</th>
                        <th width="8%">订购日期</th>
                        <th width="8%">申请时间</th>
                        <th width="18%">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order_fbs as $fb)
                        <td>{{ $fb->user_realname }}</td>
                        <td>{{ $fb->book->name }}</td>
                        <td>{{ $fb->book_isbn }}</td>
                        <td>{{ $fb->book }}</td>
                        <td>
                            <div class="row">

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
@endsection
