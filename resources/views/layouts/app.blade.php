<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/nori.css', 'resources/js/app.js'])
    @stack('css')
</head>

<body>
    @include('layouts.nav')
    <main>
        @yield('content')
    </main>

    <div class="float-container">
        <a href="" target="_blank" class="float-button trakteer">
            Trakteer
        </a>
        <a href="" target="_blank" class="float-button saweria">
            Saweria
        </a>
    </div>
    @include('layouts.footer')
</body>

</html>
