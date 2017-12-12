<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/styles.css">

    <!-- Include jQuery Mobile stylesheets -->
    {{--<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">--}}
	<!-- Include the jQuery Mobile library -->
	{{--<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>--}}

    <script src="/js/all.js"></script>

    <title> @yield('title') </title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>

@include('admin.layouts._header')
<div class="container">
    <div class="col-lg-2 col-md-2 col-xs-2" id="side-bar">
        @include('admin.layouts._side')
    </div>
    <div class="col-lg-10 col-md-10 col-xs-10" style="padding: 40px 0 40px 50px;">
        @include('admin.layouts._message')
        @yield('content')
    </div>
</div>

</body>

</html>
