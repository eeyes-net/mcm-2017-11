<!DOCTYPE html>
<html lang="{{ config('app.locale', 'en') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="西安交通大学,数学建模,数学,西交,交大,数模,数学竞赛,xjtu,mcm,math,通知,公告,报名,校内赛,队友招募,e瞳网,eeyes">
    <meta name="description" content="@yield('description', '西交数模网：掌握数学建模相关最新通知公告，校内赛报名通道、比赛队友在线招募。')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') - 西安交通大学数学建模官方网站@else西安交通大学数学建模官方网站 - 一次建模，终生受益@endif</title>
    <link rel="icon" href="/favicon.ico">
    <link rel="canonical" href="{{ request()->fullUrl() }}"/>
    <link href="{{ mix('/css/index.css') }}" rel="stylesheet">
</head>

<body>
    @include('layouts.header')
    @include('index.layouts.jumbotron')

    <main id="main">
        <div class="container-fluid">
            @yield('main')
        </div>
    </main>

    @include('layouts.footer')
    <script src="{{ mix('/js/index.js') }}"></script>
</body>
</html>
