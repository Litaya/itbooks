
@extends("layouts.frame")

@section("title", "注册")

@section("content")


<hr>

<article>感谢您的支持！欢迎您免费注册清华大学出版社用户，您将获得清华大学出版社提供多项服务。我们不会对外公开您的信息或向第三方提供用户注册资料。</article>

<hr>

<div style="margin: auto; text-align: center;">
<a href="{{route('register.basic')}}">
<button class="btn btn-primary">我知道了</button></a>
</div>

@endsection