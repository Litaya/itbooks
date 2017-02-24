<nav class="navbar navbar-default mynav">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header" >
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/admin') }}" style="margin-left: 50px;">书圈后台管理系统</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                {{--<li><a href="#">Link</a></li>--}}
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="margin-right: 50px;">
                        @if(Auth::check())
                            <i class="fa fa-user push" aria-hidden="true"></i>
                            {{ Auth::user()->username }}
                        @endif
                        {{--<span class="caret"></span>--}}
                    </a>
                    <ul class="dropdown-menu" style="margin-right: 50px">
                        <li><a href="#">个人信息</a></li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="{{ url('/admin/logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                登出
                            </a>

                            <form id="logout-form" action="{{ url('/admin/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>