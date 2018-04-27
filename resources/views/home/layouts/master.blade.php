<!DOCTYPE html>
<html lang="{{ config('app.locale', 'en') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>个人中心 - 西安交通大学数学建模官方网站</title>
    <link rel="icon" href="/favicon.ico">
    <link href="{{ mix('/css/home.css') }}" rel="stylesheet">
</head>

<body>
    @include('layouts.header')

    <div class="container-fluid">
        <div class="row" id="home">
            <nav class="col-md-2 col-sm-3 d-sm-block d-none sidebar">
                <home-layouts-sidebar></home-layouts-sidebar>
            </nav>
            <main role="main" class="col-md-10 col-sm-9 ml-sm-auto" id="main">
                @yield('main')
            </main>
        </div>
    </div>

    @include('layouts.admin_footer')

    <script src="{{ mix('/js/home.js') }}"></script>
</body>
</html>
