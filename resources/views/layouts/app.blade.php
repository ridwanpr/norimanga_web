<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="google-site-verification" content="4En0Y4O1xN_QQxyaYHva9T5Ri6ZqnvPrVjbtyTNNXqA" />
    <meta name="yandex-verification" content="e2cac82f38813c16" />
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/favicon/site.webmanifest') }}">
    @yield('meta')
    <title>@yield('title', 'Panelesia - Baca Manga, Manhwa, Manhua Bahasa Indonesia')</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/nori.css', 'resources/js/app.js'])
    @stack('css')
    @speculationRulesApi
</head>

<body>
    @include('layouts.nav')
    <main>
        @yield('content')
    </main>

    @if (!Route::is('login') && !Route::is('register'))
        <div class="float-container" style="z-index: 9999">
            <a href="https://trakteer.id/panelesia/tip?open=true" target="_blank" class="float-button trakteer">
                Trakteer
            </a>
            <a href="https://saweria.co/noricomic" target="_blank" class="float-button saweria">
                Saweria
            </a>
        </div>
    @endif

    <button id="backToTop" class="btn btn-grey position-fixed" style="bottom: 20px; left: 20px; display: none;">
        <i class="bi bi-chevron-up"></i>
    </button>


    @if ($errors->any())
        <script>
            window.LaravelErrors = @json($errors->all());
        </script>
    @endif

    @if (session('success'))
        <script>
            window.LaravelSuccessMessage = @json(session('success'));
        </script>
    @endif
    @include('layouts.footer')
    @stack('js')
</body>

</html>
