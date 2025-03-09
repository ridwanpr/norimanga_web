@extends('layouts.app')
@section('title', 'Panelesia - Baca Manga, Manhwa, Manhua Bahasa Indonesia')
@section('meta')
    <meta name="description"
        content="Baca manga, manhwa, manhua terbaru di Panelesia! Koleksi lengkap, update harian, gratis dan terjemahan Bahasa Indonesia terbaik.">
@endsection
@push('css')
    @vite('resources/css/welcome.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css"
        integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css"
        integrity="sha512-17EgCFERpgZKcm0j0fEq1YCJuyAWdz9KUtv1EjVuaOz8pDnh/0nZxmU6BBXwaaxqoi9PQXnRWqlcDB027hgv9A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        #komikCarousel .carousel-indicators {
            margin-bottom: 0.5rem;
            z-index: 5;
        }

        #komikCarousel .carousel-indicators button {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 4px;
            background-color: rgba(255, 255, 255, 0.5);
        }

        #komikCarousel .carousel-indicators button.active {
            background-color: #4caf50;
        }

        .manga-synopsis {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        #komikCarousel .carousel-control-prev,
        #komikCarousel .carousel-control-next {
            opacity: 0.8;
            width: 10%;
        }

        #komikCarousel .carousel-control-prev:hover,
        #komikCarousel .carousel-control-next:hover {
            opacity: 1;
        }

        .carousel {
            touch-action: pan-y;
        }

        @media (max-width: 768px) {
            .manga-slide {
                height: 400px;
            }

            #komikCarousel .col-md-6:first-child {
                padding-bottom: 2rem;
            }

            #komikCarousel .col-md-6:last-child {
                clip-path: none;
                height: 200px;
                position: absolute;
                bottom: 0;
                width: 100%;
                opacity: 0.3;
            }

            .manga-info {
                position: relative;
                z-index: 5;
            }
        }
    </style>
@endpush
@section('content')
    <div class="container py-1">
        <div id="komikCarousel" class="carousel slide mb-3" data-bs-touch="true" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach ($carouselManga as $c => $carousel)
                    <button type="button" data-bs-target="#komikCarousel" data-bs-slide-to="{{ $c }}"
                        class="{{ $c == 0 ? 'active' : '' }}" aria-current="{{ $c == 0 ? 'true' : 'false' }}"
                        aria-label="Slide {{ $c + 1 }}"></button>
                @endforeach
            </div>

            <div class="carousel-inner slick-carousel rounded">
                @foreach ($carouselManga as $c => $carousel)
                    <div class="carousel-item {{ $c == 0 ? 'active' : '' }}">
                        <div class="manga-slide" style="height: 380px; position: relative; overflow: hidden;">
                            <div class="slide-bg"
                                style="
                                position: absolute;
                                width: 100%;
                                height: 100%;
                                background: linear-gradient(135deg, rgba(76, 175, 80, 0.9) 0%, rgba(120, 52, 160, 0.8) 100%),
                                            url('{{ $carousel->detail->cover }}') center center/cover no-repeat;
                                filter: blur(5px);
                                z-index: 1;
                            ">
                            </div>

                            <div class="container-fluid h-100 position-relative" style="z-index: 2;">
                                <div class="row h-100">
                                    <div class="col-md-6 d-flex flex-column justify-content-center text-white p-4">
                                        <div class="manga-info">
                                            <h2 class="fw-bold">{{ $carousel->title }}</h2>
                                            <p class="my-2 manga-synopsis">
                                                {{ Str::limit($carousel->detail->synopsis, 200) }}</p>
                                            <div class="genre-tags mb-3">
                                                @if (isset($carousel->genres))
                                                    @foreach ($carousel->genres as $genre)
                                                        <span class="badge bg-dark me-1">{{ $genre->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge bg-dark me-1">Action</span>
                                                    <span class="badge bg-dark me-1">Drama</span>
                                                    <span class="badge bg-dark me-1">Shounen</span>
                                                @endif
                                            </div>
                                            <a href="{{ route('manga.show', $carousel->slug) }}"
                                                class="btn btn-warning text-dark fw-bold px-4 py-2">
                                                Mulai Baca →
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-6 p-0 h-100"
                                        style="clip-path: polygon(10% 0, 100% 0, 100% 100%, 0% 100%);">
                                        <div class="cover-image h-100 w-100"
                                            style="
                                            background: url('{{ $carousel->detail->cover }}') center center/contain no-repeat;
                                            background-position: right center;
                                            padding-left: 10%;
                                        ">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- <div class="hero-bg"></div> --}}
        <section class="featured">
            <div class="row mb-2">
                <div class="col-lg-12">
                    <h2 class="featured_title fs-4 fw-bold"><span class="text-primary">Komik </span>Unggulan </h2>
                </div>
            </div>
            <div class="row g-2">
                @foreach ($featureds as $featured)
                    <div class="col-4 col-md-3 col-lg-2">
                        <div class="image-container">
                            <a href="{{ route('manga.show', $featured->slug) }}">
                                <img src="{{ $featured->cover }}" class="img-fluid rounded fixed-size-img"
                                    alt="{{ $featured->title }}">
                            </a>
                            <div class="image-title text-capitalize">
                                {{ \Str::limit(\Str::title(strtolower($featured->title)), 40, '...') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        <div class="mt-4 mb-3 bg-dark p-4 rounded shadow-sm">
            <h1 class="fs-5 mb-3 text-primary">Panelesia – Portal Baca Manga, Manhwa & Manhua Bahasa Indonesia</h1>
            <p class="mb-0">
                Selamat datang di Panelesia, situs baca komik online yang menyediakan koleksi manga, manhwa, dan manhua
                dalam bahasa Indonesia.
            </p>
            <p class="mb-0">
                Dukung kami dengan donasi melalui <a href="https://trakteer.id/noricomic/tip?open=true" target="_blank"
                    class="badge bg-danger text-decoration-none">Trakteer</a> dan <a href="https://saweria.co/noricomic"
                    target="_blank" class="badge bg-warning text-dark text-decoration-none">Saweria</a>
            </p>
        </div>
        <div class="row">
            <div class="col-12 col-md-8">
                @if ($projects->count() > 0)
                    <section class="latest-project">
                        <h2 class="fs-4 fw-bold mb-3"><span class="text-primary">Project</span> Update</h2>
                        <div class="row g-2">

                            @foreach ($projects as $project)
                                <div class="col-6 col-md-3">
                                    <a href="{{ route('manga.show', $project->slug) }}" class="text-decoration-none">
                                        <div class="image-container mb-1">
                                            <img src="{{ $project->cover }}" class="img-fluid rounded fixed-size-latest"
                                                alt="{{ $project->title }}" loading="lazy">
                                            <div class="image-title text-capitalize">
                                                {{ \Str::limit(\Str::title(strtolower($project->title)), 40, '...') }}
                                            </div>
                                        </div>
                                    </a>
                                    @foreach ($project->chapters as $chapter)
                                        <a href="{{ route('manga.reader', [$project->slug, $chapter->slug]) }}"
                                            class="text-decoration-none small">
                                            <div
                                                class="d-flex justify-content-between text-decoration-none bg-body-tertiary p-2 text-body mb-1 rounded border">
                                                <small>{{ $chapter->title }}</small>
                                                <small
                                                    class="text-secondary">{{ $chapter->created_at->diffForHumans() }}</small>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endforeach

                            <div class="col-12">
                                <div class="d-flex justify-content-center">
                                    <a href="#" class="btn btn-grey mt-2 mb-3">Lihat Semua</a>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif

                <section class="latest-update">
                    <h2 class="fs-4 mb-3 fw-bold"><span class="text-primary">Update</span> Terbaru</h2>
                    <div class="row g-2">
                        @foreach ($latestUpdate as $update)
                            <div class="col-6 col-md-4 col-lg-3">
                                <a href="{{ route('manga.show', $update->slug) }}" class="text-decoration-none">
                                    <div class="position-relative">
                                        <img src="{{ $update->cover }}"
                                            onerror="this.src='https://placehold.co/250x300';"
                                            class="img-fluid rounded mb-1 fixed-size-latest"
                                            alt="{{ $update->title }} cover image" loading="lazy">
                                        @switch($update->type)
                                            @case('Manga')
                                                <div
                                                    class="position-absolute top-0 start-0 bg-danger text-white d-flex align-items-center p-1 rounded-br">
                                                    <small class="comic-type">Manga</small>
                                                </div>
                                            @break

                                            @case('Manhwa')
                                                <div
                                                    class="position-absolute top-0 start-0 bg-success text-white d-flex align-items-center p-1 rounded-br">
                                                    <small class="comic-type">Manhwa</small>
                                                </div>
                                            @break

                                            @case('Manhua')
                                                <div
                                                    class="position-absolute top-0 start-0 bg-warning text-white d-flex align-items-center p-1 rounded-br">
                                                    <small class="comic-type">Manhua</small>
                                                </div>
                                            @break
                                        @endswitch
                                        <div class="image-title">
                                            <span class="text-capitalize">
                                                {{ \Str::limit(\Str::title(strtolower($update->title)), 40, '...') }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                                @foreach ($update->chapters as $chapter)
                                    <a href="{{ route('manga.reader', [$update->slug, $chapter->slug]) }}"
                                        class="text-decoration-none small">
                                        <div
                                            class="d-flex justify-content-between text-decoration-none bg-body-tertiary p-2 text-body mb-1 rounded border">
                                            <small
                                                style="font-size: 12px">{{ \Str::limit($chapter->title, 18, '') }}</small>
                                            <small class="text-secondary"
                                                style="font-size: 12px">{{ $chapter->created_at->diffForHumans(['short' => true]) }}</small>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endforeach

                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('manga.grid-list') }}" class="btn btn-grey mt-3 mb-2">Lihat Semua</a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-12 col-md-4 mt-4">
                <section class="trendings rounded shadow-sm p-3 bg-dark mb-4">
                    <h2 class="fs-5 fw-bold mb-3 d-flex align-items-center">
                        <i class="bi bi-fire me-2"></i> Trending
                    </h2>

                    <ul class="nav nav-pills nav-fill mb-3 bg-dark rounded p-1" id="trendingPills" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active btn-sm d-flex align-items-center justify-content-center"
                                id="today-pill" data-bs-toggle="pill" data-bs-target="#today" type="button"
                                role="tab" aria-controls="today" aria-selected="true">
                                <i class="bi bi-calendar-date me-1"></i> Today
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link btn-sm d-flex align-items-center justify-content-center"
                                id="daily-pill" data-bs-toggle="pill" data-bs-target="#daily" type="button"
                                role="tab" aria-controls="daily" aria-selected="false">
                                <i class="bi bi-calendar-day me-1"></i> Weekly
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link btn-sm d-flex align-items-center justify-content-center"
                                id="weekly-pill" data-bs-toggle="pill" data-bs-target="#weekly" type="button"
                                role="tab" aria-controls="weekly" aria-selected="false">
                                <i class="bi bi-calendar-week me-1"></i> Monthly
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="trendingPillsContent">
                        <div class="tab-pane fade show active" id="today" role="tabpanel"
                            aria-labelledby="today-pill">
                            @foreach ($trendingDaily as $daily)
                                <a href="{{ route('manga.show', $daily->slug) }}" class="text-decoration-none">
                                    <div class="card mb-1 trending-card border-0 overflow-hidden">
                                        <div class="row g-0">
                                            <div class="col-3 trending-img-container">
                                                <div class="manga-cover-wrapper">
                                                    <img src="{{ $daily->detail->cover }}" class="trending-img"
                                                        alt="{{ $daily->title }}">
                                                </div>
                                            </div>
                                            <div class="col-9 d-flex align-items-center">
                                                <div class="card-body py-1 px-2">
                                                    <h3 class="card-title fs-6 mb-1 manga-title" data-bs-toggle="tooltip"
                                                        title="{{ $daily->title }}">
                                                        {{ $daily->title }}
                                                    </h3>
                                                    <p class="card-text text-secondary small mb-0">
                                                        @foreach (array_slice($daily->genres->toArray(), 0, 8) as $index => $dailyGenre)
                                                            {{ $dailyGenre['name'] }}{{ $index < count(array_slice($daily->genres->toArray(), 0, 8)) - 1 ? ',' : '' }}
                                                        @endforeach
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="tab-pane fade" id="daily" role="tabpanel" aria-labelledby="daily-pill">
                            @foreach ($trendingWeekly as $weekly)
                                <a href="{{ route('manga.show', $weekly->slug) }}" class="text-decoration-none">
                                    <div class="card mb-2 trending-card border-0 overflow-hidden">
                                        <div class="row g-0">
                                            <div class="col-3 trending-img-container">
                                                <div class="manga-cover-wrapper">
                                                    <img src="{{ $weekly->detail->cover }}" class="trending-img"
                                                        alt="{{ $weekly->title }}">
                                                </div>
                                            </div>
                                            <div class="col-9 d-flex align-items-center">
                                                <div class="card-body py-1 px-2">
                                                    <h3 class="card-title fs-6 mb-1 manga-title" data-bs-toggle="tooltip"
                                                        title="{{ $weekly->title }}">
                                                        {{ $weekly->title }}
                                                    </h3>
                                                    <p class="card-text text-secondary small mb-0">
                                                        @foreach (array_slice($weekly->genres->toArray(), 0, 8) as $index => $weeklyGenre)
                                                            {{ $weeklyGenre['name'] }}{{ $index < count(array_slice($weekly->genres->toArray(), 0, 8)) - 1 ? ',' : '' }}
                                                        @endforeach
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="tab-pane fade" id="weekly" role="tabpanel" aria-labelledby="weekly-pill">
                            @foreach ($trendingMonthly as $monthly)
                                <a href="{{ route('manga.show', $monthly->slug) }}" class="text-decoration-none">
                                    <div class="card mb-2 trending-card border-0 overflow-hidden">
                                        <div class="row g-0">
                                            <div class="col-3 trending-img-container">
                                                <div class="manga-cover-wrapper">
                                                    <img src="{{ $monthly->detail->cover }}" class="trending-img"
                                                        alt="{{ $monthly->title }}">
                                                </div>
                                            </div>
                                            <div class="col-9 d-flex align-items-center">
                                                <div class="card-body py-1 px-2">
                                                    <h3 class="card-title fs-6 mb-1 manga-title" data-bs-toggle="tooltip"
                                                        title="{{ $monthly->title }}">
                                                        {{ $monthly->title }}
                                                    </h3>
                                                    <p class="card-text text-secondary small mb-0">
                                                        @foreach (array_slice($monthly->genres->toArray(), 0, 8) as $index => $monthlyGenre)
                                                            {{ $monthlyGenre['name'] }}{{ $index < count(array_slice($monthly->genres->toArray(), 0, 8)) - 1 ? ',' : '' }}
                                                        @endforeach
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>
                <section class="genres mt-4">
                    <h2 class="fs-4 fw-bold mt-3 mt-md-0 mb-3"><i class="bi bi-grid"></i> Genre</h2>
                    <div class="genre-list bg-dark">
                        <div class="row row-cols-2 row-cols-sm-3 g-2">
                            @foreach ($genres as $genre)
                                <div class="col">
                                    <a href="{{ route('manga.grid-list', ['genre' => $genre->slug]) }}"
                                        class="genre-item text-capitalize">{{ $genre->name }}</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"
        integrity="sha512-HGOnQO9+SP1V92SrtZfjqxxtLmVzqZpjFFekvzZVWoiASSQgSr4cw9Kqd2+l8Llp4Gm0G8GIFJ4ddwZilcdb8A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('.slick-carousel').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: true,
                arrows: true,
                infinite: true,
                adaptiveHeight: true,
                autoplay: true,
                autoplaySpeed: 3000,
                prevArrow: '<button type="button" class="slick-prev">&lt;</button>',
                nextArrow: '<button type="button" class="slick-next">&gt;</button>',
            });

            $('#komikCarousel').removeClass('carousel slide').removeAttr('data-bs-ride data-bs-touch');
        });
    </script>
@endpush
