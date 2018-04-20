<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>个人中心 - 西安交通大学数学建模官方网站</title>
    <link rel="icon" href="/favicon.ico">
    <link href="{{ mix('/css/home.css') }}" rel="stylesheet">
</head>

<body>
    @include('layouts.header')

    <div class="container-fluid">
        <div class="row" id="home">
            <nav class="col-sm-3 col-md-2 d-sm-block sidebar">
                <home-layouts-sidebar></home-layouts-sidebar>
            </nav>
            <main role="main" class="col-sm-9 ml-sm-auto col-md-10 pt-3">
                @yield('main')
            </main>
        </div>
        <div class="row">
            <footer class="footer col-sm-9 ml-sm-auto col-md-10 pt-3">
                @include('layouts.footer')
            </footer>
        </div>
    </div>

    <script src="{{ mix('/js/home.js') }}"></script>
</body>
</html>
