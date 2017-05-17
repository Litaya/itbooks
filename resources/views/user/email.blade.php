@extends('layouts.frame')

@section('title','邮箱')

@section('content')
    <div class="col-xs-12">
        @if(isset(Auth::user()->email))
            <p>您的邮箱: {{ Auth::user()->email }}</p>
            @if( !Auth::user()->email_status )
                <button type="button" class="btn btn-primary btn-lg btn-block" id="send_email" onclick="send_email()">发送验证邮件</button>
            @else
                <p style="color:#00CC66"><small>已验证</small></p>
            @endif
        @else
            <form action="{{ route('user.email.store') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="email" >邮箱</label>
                    <input type="email" name="email" class="form-control" placeholder="请输入您的邮箱">
                </div>
                <input type="submit" value="验证" class="btn btn-primary">
            </form>
        @endif

    </div>

    <script>
        function send_email() {
            $.ajax({
                'url': "{{ route("user.email.send_cert") }}",
                "method": "get"
            });
        }
    </script>
@stop