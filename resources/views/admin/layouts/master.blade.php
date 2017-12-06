<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">
    <title>西安交通大学数学建模官方网站</title>
    <link href="/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/jumbotron.css" rel="stylesheet">
</head>

<body>

    @include('index.layouts.navbar')

    @include('index.layouts.jumbotron')

    <main id="main">
        <div class="container">
            @yield('main')
        </div>
    </main>

    @include('index.layouts.footer')

    <script src="/dist/js/jquery.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="/dist/js/popper.min.js"></script>
    <script src="/dist/js/bootstrap.min.js"></script>
    <script src="/dist/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
