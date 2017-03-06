@extends('layouts.frame')

@section('title','邮箱')

@section('content')
    <div class="col-xs-12" style="text-align: center;">
        <p>您的邮箱: {{ Auth::user()->email }}</p>
        @if( !Auth::user()->email_status )
            <button type="button" class="btn btn-primary btn-lg btn-block" id="send_email" onclick="send_email()">发送验证邮件</button>
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