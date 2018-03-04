<header>
    <nav class="navbar navbar-expand-md fixed-top">
        <a class="navbar-brand" href="{{ url('/') }}">西交数模</a>
        <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item @if(request()->is('/') || request()->is('post') || request()->is('post/*')) active @endif">
                    <a class="nav-link" href="{{ url('/post') }}">公告</a>
                </li>
                <li class="nav-item @if(request()->is('match')) active @endif">
                    <a class="nav-link" href="{{ url('/match') }}">竞赛报名</a>
                </li>
                <li class="nav-item @if(request()->is('recruit')) active @endif">
                    <a class="nav-link" href="{{ url('/recruit') }}">发起组队</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                @if (auth()->check())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/home') }}">欢迎您：{{ auth()->user()->name }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/home') }}">个人中心</a>
                    </li>
                    @if (auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/admin') }}">后台管理</a>
                        </li>
                    @endif
                    <li>
                        <form action="{{ url('/logout') }}" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-default navbar-btn">注销</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ action('Auth\LoginController@login') }}">CAS登录</a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
</header>