<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="menu_trigger">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/home">书圈</a>

            <ul class="dropdown-menu" aria-labelledby="menu_trigger" id="menu-list">
                {{--<li><a href="#">Link</a></li>--}}
                {{--<li><a href="#">Link</a></li>--}}
                {{--<li><a href="#">Link</a></li>--}}
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" >
                            {{ Auth::user()->username }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                @endif
            </ul>
        </div>

    </div><!-- /.container-fluid -->
</nav>
