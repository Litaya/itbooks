<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/styles.css">
    <script src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
    <title> @yield('title') </title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        window.Laravel = <?php echo json_encode([
			'csrfToken' => csrf_token(),
		]); ?>
    </script>
</head>

<body>
@include('layouts._header')
<div class="container" style="margin: 0 20px 0 20px;padding: 0">
    @include('layouts._message')
    @yield('content')
</div>

<script src="/js/app.js"></script>

</body>

</html>