<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button style="padding: 0; margin: 0;" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <img src='/img/avatar.png' alt="" style="margin: 0; padding: 0; width: 45px; height: 45px; border-radius: 25px" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"/>
{{--        <img src="{{ isset(Auth::user()->headimgurl)?Auth::user()->headimgurl:'/img/avatar.png' }}" alt="" style="margin: 0; padding: 0; width: 45px; height: 45px; border-radius: 25px" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"/>--}}
      </button>
      <a class="navbar-brand" href="{{route('index')}}">书圈</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        {{--        <li class="{{(Request::is('book/*') or Request::is('book')) ? "active":""}}"><a href="{{route('book.index')}}">图书</a></li>--}}
        <li class="{{Request::is('resource*') ? "active":""}}"><a href="{{route('resource.index')}}">资源列表</a></li>
        <li class="{{Request::is('bookreq*') ? "active":""}}"><a href="{{route('bookreq.record')}}">样书申请</a></li>
        <li class="{{Request::is('conference*' ? "active":"")}}"><a href="{{route('conference.index')}}">会议列表</a></li>
        {{--<li class="{{Request::is('cert*') ? "active":""}}"><a href="{{route('cert.index')}}">认证</a></li>--}}
      <!--li class="{{Request::is('personal') ? "active":""}}"><a href="#">个人空间</a></li-->
        @if(Auth::user())
          <li><a href="{{ route('user.index') }}">个人资料</a></li>
          <li>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              退出登录
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
            </form>
          </li>
        @endif
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>