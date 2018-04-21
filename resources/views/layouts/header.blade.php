<header>
    <nav class="navbar navbar-light navbar-expand-md fixed-top">
        <a class="navbar-brand" href="{{ url('/') }}">西交数模</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item @if(request()->is('/') || request()->is('post') || request()->is('post/*')) active @endif">
                    <a class="nav-link" href="{{ url('/') }}">公告</a>
                </li>
                <li class="nav-item @if(request()->is('match')) active @endif">
                    <a class="nav-link" href="{{ url('/match') }}">竞赛报名</a>
                </li>
                <li class="nav-item @if(request()->is('recruit')) active @endif">
                    <a class="nav-link" href="{{ url('/recruit') }}">队伍招募</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                @if (auth()->check())
                    <?php
                    /** @var \App\User $user */
                    $user = auth()->user();
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/home') }}">欢迎您：{{ $user->name }}</a>
                    </li>
                    <li class="nav-item @if(request()->is('home')) active @endif">
                        <a class="nav-link" href="{{ url('/home') }}">个人中心</a>
                    </li>
                    @if ($user->isAdmin())
                        <li class="nav-item @if(request()->is('admin')) active @endif">
                            <a class="nav-link" href="{{ url('/admin') }}">后台管理</a>
                        </li>
                    @endif
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-outline-secondary navbar-btn">注销</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <form action="{{ route('login') }}" method="GET">
                            <button type="submit" class="btn btn-outline-secondary navbar-btn">CAS登录</button>
                        </form>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
</header>