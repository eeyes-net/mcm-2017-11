<nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">数学建模</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ action('Index\PostController@index') }}">公告 <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ action('Index\MatchController@index') }}">竞赛报名</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ action('Index\RecruitController@index') }}">发起组队</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                @if (auth()->check())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ action('Home\TeamController@index') }}">欢迎您：{{ auth()->user()->name }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ action('Home\TeamController@index') }}">个人中心</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin">后台管理</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ action('Auth\LoginController@login') }}">CAS登录</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
