<!DOCTYPE html>
<html lang="{{ config('app.locale', 'en') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>西安交通大学数学建模官方网站</title>
    <link rel="icon" href="/favicon.ico">
    <link href="/css/index.css" rel="stylesheet">
</head>

<body>
    @include('index.layouts.navbar')
    @include('index.layouts.jumbotron')

    <main id="main">
        <div class="container">
            @yield('main')
        </div>
    </main>

    @include('layouts.footer')
    <script src="/js/index.js"></script>
</body>
</html>
