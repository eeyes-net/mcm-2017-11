<!DOCTYPE html>
<html lang="{{ config('app.locale', 'en') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="西安交通大学,数学建模,数学,西交,交大,数模,数学竞赛,xjtu,mcm,math,通知,公告,报名,校内赛,队友招募,e瞳网,eeyes">
    <meta name="description" content="西交数模网：掌握数学建模相关最新通知公告，校内赛报名通道、比赛队友在线招募。@yield('message')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') - 西安交通大学数学建模官方网站@else西安交通大学数学建模官方网站 - 一次建模，终生受益@endif</title>
    <link rel="icon" href="/favicon.ico">
    <link rel="canonical" href="{{ request()->fullUrl() }}"/>
    <link href="{{ mix('/css/index.css') }}" rel="stylesheet">
    <style>
        .error {
            display: flex;
            position: relative;
            height: calc(100vh - 3.5rem);
            align-items: center;
            justify-content: center;
        }

        .error .message {
            padding: 20px;
            font-size: 36px;
            font-weight: lighter;
            text-align: center;
            color: #636b6f;
        }
    </style>
</head>

<body>
    @if ($exception->getStatusCode() === 404)
        @include('layouts.header')
    @else
        <header>
            <nav class="navbar navbar-light navbar-expand-md fixed-top">
                <a class="navbar-brand" href="{{ url('/') }}">西交数模</a>
            </nav>
        </header>
    @endif
    <div class="error">
        <div class="message">
            @yield('message')
        </div>
    </div>
    @include('layouts.footer')
</body>
</html>