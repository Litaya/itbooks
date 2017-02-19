<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <title> @yield('title') </title>
</head>

<body>
@include('layouts._header')
<div class="container">
@include('layouts._message')
@yield('content')
</div>

<script src="/js/app.js"></script>

</body>

</html>