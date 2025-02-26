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
    @turnstileScripts()
</head>

<body>
    @include('layouts.nav')
    <main>
        @yield('content')
    </main>

    <div class="float-container">
        <a href="https://trakteer.id/noricomic/tip?open=true" target="_blank" class="float-button trakteer">
            Trakteer
        </a>
        <a href="https://saweria.co/noricomic" target="_blank" class="float-button saweria">
            Saweria
        </a>
    </div>
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
