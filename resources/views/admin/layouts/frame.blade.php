<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/styles.css">
    <script src="/js/all.js"></script>

    <title> @yield('title') </title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>

<script src="/js/jquery-plugin.js"></script>

@include('admin.layouts._header')
<div class="container">
    <div class="col-lg-2" id="side-bar">
        @include('admin.layouts._side')
    </div>
    <div class="col-lg-10" style="padding: 40px 0 40px 50px;">
        @include('admin.layouts._message')
        @yield('content')
    </div>
</div>

</body>

</html>
