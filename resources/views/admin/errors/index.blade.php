<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <title> @yield('title') </title>
</head>

<body>
@include('admin.layouts._header')
<div class="container">
    <div class="col-lg-12" style="padding: 40px 50px 0 50px;">
        <div class="panel panel-danger">
            <div class="panel-body">
                {{ $message }}
            </div>
        </div>
    </div>
</div>
<script src="/js/app.js"></script>

</body>

</html>