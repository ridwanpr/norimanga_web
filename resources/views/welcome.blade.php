@extends('layouts.app')
@section('title', 'Panelesia - Baca Manga, Manhwa, Manhua Bahasa Indonesia')
@section('meta')
    <meta name="description"
        content="Baca manga, manhwa, manhua terbaru di Panelesia! Koleksi lengkap, update harian, gratis dan terjemahan Bahasa Indonesia terbaik.">
@endsection
@push('css')
    <style>
        .sidebar-text {
            font-size: 15px;
        }

        @media (max-width: 768px) {
            .sidebar-text {
                font-size: 13px;
            }
        }

        .trending-card {
            transition: all 0.2s ease;
            border-radius: 0.5rem;
        }

        .trending-img-container {
            padding: 7px 0 7px 7px;
            height: 70px;
        }

        .manga-cover-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
            border-radius: 0.375rem;
        }

        .trending-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .manga-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.2;
            max-height: 2.4em;
            font-size: 0.85rem !important;
            margin-bottom: 0.2rem !important;
        }

        .card-text {
            font-size: 0.7rem;
            line-height: 1;
        }

        .nav-pills .nav-link {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.8rem;
        }

        .trendings .nav-pills {
            margin-bottom: 0.75rem !important;
        }

        .trendings h2 {
            margin-bottom: 0.5rem !important;
        }
    </style>
@endpush
@section('content')
    <div class="container py-1">
        {{-- <div class="hero-bg"></div> --}}
        <section class="featured">
            <div class="row mb-2">
                <div class="col-lg-12">
                    <h2 class="featured_title fs-4 fw-bold"><span class="text-primary">Komik </span>Unggulan </h2>
                </div>
            </div>
            <div class="row g-2">
                @foreach ($featureds as $featured)
                    <div class="col-4 col-md-2">
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
                {{-- <div class="alert alert-danger" role="alert">
                    <i class="bi bi-info-circle-fill text-danger"></i> <strong>Bookmark</strong> web <a
                        href="https://noricomic.pages.dev/" class="text-decoration-none fw-bold"
                        target="_blank">Noricomic</a> untuk
                    selalu dapatkan akses ke domain terbaru.
                </div> --}}
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
                            <div class="col-6 col-md-3">
                                <a href="{{ route('manga.show', $update->slug) }}" class="text-decoration-none">
                                    <div class="position-relative">
                                        <img src="{{ $update->cover }}" onerror="this.src='https://placehold.co/250x300';"
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
                                                style="font-size: 11px">{{ \Str::limit($chapter->title, 16, '') }}</small>
                                            <small class="text-secondary"
                                                style="font-size: 11px">{{ $chapter->created_at->diffForHumans(['short' => true]) }}</small>
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
                                id="today-pill" data-bs-toggle="pill" data-bs-target="#today" type="button" role="tab"
                                aria-controls="today" aria-selected="true">
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
                                                    <h5 class="card-title fs-6 mb-1 manga-title" data-bs-toggle="tooltip"
                                                        title="{{ $daily->title }}">
                                                        {{ $daily->title }}
                                                    </h5>
                                                    <p class="card-text text-secondary small mb-0">
                                                        Action, Adventure
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
                                                    <h5 class="card-title fs-6 mb-1 manga-title" data-bs-toggle="tooltip"
                                                        title="{{ $weekly->title }}">
                                                        {{ $weekly->title }}
                                                    </h5>
                                                    <p class="card-text text-secondary small mb-0">
                                                        Action, Adventure
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
                                                    <h5 class="card-title fs-6 mb-1 manga-title" data-bs-toggle="tooltip"
                                                        title="{{ $monthly->title }}">
                                                        {{ $monthly->title }}
                                                    </h5>
                                                    <p class="card-text text-secondary small mb-0">
                                                        Action, Adventure
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
