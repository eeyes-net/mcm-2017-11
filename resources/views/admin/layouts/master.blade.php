<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>后台管理 - 西安交通大学数学建模官方网站</title>
    <link rel="icon" href="/favicon.ico">
    <link href="{{ mix('/css/admin.css') }}" rel="stylesheet">
</head>

<body>
    @include('layouts.header')

    <div class="container-fluid">
        <div class="row" id="admin">
            <nav class="col-sm-3 col-md-2 d-sm-block sidebar">
                <admin-layouts-sidebar></admin-layouts-sidebar>
            </nav>
            <main role="main" class="col-sm-9 ml-sm-auto col-md-10" id="main">
                @yield('main')
            </main>
        </div>
    </div>

    @include('layouts.admin_footer')

    <script src="{{ mix('/js/admin.js') }}"></script>
</body>
</html>
