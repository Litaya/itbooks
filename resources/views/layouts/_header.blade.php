<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="{{route('index')}}">书圈</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="{{(Request::is('book/*') or Request::is('book')) ? "active":""}}"><a href="{{route('book.index')}}">图书</a></li>
        <li class="{{Request::is('resource*') ? "active":""}}"><a href="{{route('resource.index')}}">资源</a></li>
        <li class="{{Request::is('bookreq*') ? "active":""}}"><a href="{{route('bookreq.index')}}">样书申请</a></li>
        <li class="{{Request::is('conference*' ? "active":"")}}"><a href="{{route('conference.index')}}">会议</a></li>
        <li class="{{Request::is('cert*') ? "active":""}}"><a href="{{route('cert.create')}}">认证</a></li>
        <!--li class="{{Request::is('personal') ? "active":""}}"><a href="#">个人空间</a></li-->
      </ul>

      @if(Auth::user())
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->username}}<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{{ route('user.index') }}">个人资料</a></li>
            <li role="separator" class="divider"></li>
            <li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                登出
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
             </li>
          </ul>
        </li>
      </ul>
      @endif
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>