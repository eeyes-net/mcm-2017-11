<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">切换导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">西交数模</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li @if(request()->is('/') || request()->is('post')) class="active" @endif><a href="{{ url('/post') }}">公告</a></li>
                <li @if(request()->is('match')) class="active" @endif><a href="{{ url('/match') }}">竞赛报名</a></li>
                <li @if(request()->is('recruit')) class="active" @endif><a href="{{ url('/recruit') }}">发起组队</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (auth()->check())
                    <li>
                        <p class="navbar-text">欢迎：{{ auth()->user()->name }}</p>
                    </li>
                    <li>
                        <form action="{{ url('/logout') }}" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-default navbar-btn">注销</button>
                        </form>
                    </li>
                @else
                    <li>
                        <form action="{{ url('/login') }}" method="GET">
                            <button type="submit" class="btn btn-default navbar-btn">CAS登录</button>
                        </form>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
