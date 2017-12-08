<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">
    <title>后台管理 - 西安交通大学数学建模官方网站</title>
    <link href="/css/admin.css" rel="stylesheet">
</head>

<body>
    @include('admin.layouts.header')

    <div class="container-fluid">
        <div class="row" id="admin">
            <nav class="col-sm-3 col-md-2 d-sm-block bg-light sidebar">
                <admin-layouts-sidebar></admin-layouts-sidebar>
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

    <script src="/js/admin.js"></script>
</body>
</html>
