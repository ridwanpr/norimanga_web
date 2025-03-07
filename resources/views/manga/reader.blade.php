@extends('layouts.app')
@section('meta')
    <meta name="description"
        content="Baca Manga {{ $chapter->manga->title }} {{ $chapter->chapter_number }} bahasa Indonesia gratis di Panelesia! Terjemahan Bahasa Indonesia gratis.">
    <meta property="og:title" content="{{ $chapter->title }} - {{ $chapter->manga->title }} - Panelesia">
    <meta property="og:description" content="{{ Str::limit($chapter->manga->detail->synopsis, 150) }}">
    <meta property="og:image" content="{{ $chapter->manga->detail->cover }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
@endsection
@section('title', "Baca {$chapter->manga->title} {$chapter->title} Bahasa Indonesia - Panelesia - Manga Indonesia")
@push('css')
    @vite('resources/css/reader.css')
@endpush

@section('content')
    <div class="container">
        <div class="top-section">
            <div class="text-center mb-3">
                <h1 class="title fs-4 fw-bold">
                    {{ $chapter->manga->title }} .
                    {{ $chapter->title }}
                </h1>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-dark p-3 rounded-3 d-flex flex-wrap justify-content-center text-white">
                    <li class="breadcrumb-item">
                        <a href="#" class="text-white text-decoration-none">Beranda</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('manga.show', $chapter->manga->slug) }}" class="text-white text-decoration-none">
                            {{ $chapter->manga->title }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-white" aria-current="page">
                        {{ $chapter->title }}
                    </li>
                </ol>
            </nav>
            <p>
                Baca manga terbaru {{ $chapter->manga->title }} ID {{ $chapter->title }} di Panelesia. Komik manga
                {{ $chapter->manga->title }} Bahas Indo selalu
                diperbarui di Panelesia. Jangan lupa untuk membaca pembaruan komik lainnya. Daftar koleksi komik Panelesia
                ada
                di menu Daftar Komik.
            </p>
            <div class="alert alert-warning w-100" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-warning"></i> <strong>Perhatian!</strong> Jika
                menemukan gambar yang error, rusak atau tidak tampil. <a href="https://forms.gle/woMnsABJ4DJhsN1B9"
                    target="_blank" class="alert-link">Laporkan</a>
                kepada kami.
            </div>
        </div>
        <div class="nav-ch-section mt-4">
            <div class="d-flex justify-content-between gap-2">
                @if ($prevChapter)
                    <a href="{{ route('manga.reader', [$chapter->manga->slug, $prevChapter->slug]) }}"
                        class="btn btn-grey">
                        <i class="bi bi-chevron-left"> Prev</i>
                    </a>
                @else
                    <button class="btn btn-grey" disabled>
                        <i class="bi bi-chevron-left"> Prev</i>
                    </button>
                @endif

                <div class="d-flex gap-2 justify-content-between">
                    <a href="{{ route('manga.show', $chapter->manga->slug) }}" class="btn btn-grey">
                        <i class="bi bi-list"></i>
                    </a>
                    <a href="javascript:void(0)" class="btn btn-grey setting-btn d-none">
                        <i class="bi bi-gear"></i>
                    </a>
                    <a href="javascript:void(0)" id="report-error" class="btn btn-danger setting-btn ms-1 text-white">
                        <i class="bi bi-exclamation-circle"></i>
                    </a>
                </div>

                @if ($nextChapter)
                    <a href="{{ route('manga.reader', [$chapter->manga->slug, $nextChapter->slug]) }}"
                        class="btn btn-grey">
                        <i class="bi bi-chevron-right"> Next</i>
                    </a>
                @else
                    <button class="btn btn-grey" disabled>
                        <i class="bi bi-chevron-right"> Next</i>
                    </button>
                @endif
            </div>
        </div>
    </div>
    <div class="container p-0 px-md-2">
        <div class="reader mt-3 mt-md-4" id="reader">
            <div class="reader-container">
                <div class="reader-img">
                    <img src="https://s2.panelesia.my.id/panelesia.my.id.webp" alt="panelesia baca komik" class="img-fluid">
                    <img src="{{ asset('assets/img/panelesia-kelebihan-fitur.png') }}" class="img-fluid my-1"
                        alt="fitur dan kelebihan panelesia">
                    @foreach ($images as $index => $image)
                        <img src="{{ $image }}" class="img-fluid"
                            alt="{{ $chapter->manga->title }} {{ $chapter->title }}"
                            onerror="this.onerror=null;this.src='{{ asset('assets/img/no-image.png') }}'"
                            @if ($index > 2) loading="lazy" @endif>
                    @endforeach
                    <img src="https://s2.panelesia.my.id/panelesia.my.id.webp" alt="panelesia baca komik" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
    <div class="container nav-bottom mt-4">
        <div class="nav-ch-section mt-4">
            <div class="d-flex justify-content-between gap-2">
                @if ($prevChapter)
                    <a href="{{ route('manga.reader', [$chapter->manga->slug, $prevChapter->slug]) }}"
                        class="btn btn-grey">
                        <i class="bi bi-chevron-left"> Prev</i>
                    </a>
                @else
                    <button class="btn btn-grey" disabled>
                        <i class="bi bi-chevron-left"> Prev</i>
                    </button>
                @endif

                <div class="d-flex gap-2 justify-content-between">
                    <a href="{{ route('manga.show', $chapter->manga->slug) }}" class="btn btn-grey">
                        <i class="bi bi-list"></i>
                    </a>
                </div>

                @if ($nextChapter)
                    <a href="{{ route('manga.reader', [$chapter->manga->slug, $nextChapter->slug]) }}"
                        class="btn btn-grey">
                        <i class="bi bi-chevron-right"> Next</i>
                    </a>
                @else
                    <button class="btn btn-grey" disabled>
                        <i class="bi bi-chevron-right"> Next</i>
                    </button>
                @endif
            </div>
        </div>
        <div style="height: 25px; overflow-y: auto;" class="tags mt-4 py-0 px-1 bg-dark rounded">
            <small class="text-light">Tags: baca manga {{ $chapter->manga->title }} {{ $chapter->title }} bahasa
                Indonesia,
                komik {{ $chapter->manga->title }} {{ $chapter->title }} bahasa Indonesia, baca
                {{ $chapter->manga->title }} {{ $chapter->title }} online, {{ $chapter->manga->title }}
                {{ $chapter->title }} bab, {{ $chapter->manga->title }} {{ $chapter->title }} chapter,
                {{ $chapter->manga->title }} {{ $chapter->title }} high quality, {{ $chapter->manga->title }}
                {{ $chapter->title }} manga scan, {{ now()->format('F d, Y') }}, Panelesia</small>
        </div>

        <div class="container my-4">
            <h2 class="fs-4 mb-3 fw-bold"><span class="text-primary">Kamu</span> Mungkin Suka</h2>
            <div class="scroll-wrapper">
                <div class="scroll-content">
                    @foreach ($alsoRead as $manga)
                        <div class="manga-card">
                            <a href="{{ route('manga.show', $manga->slug) }}">
                                <img src="{{ $manga->detail->cover }}" alt="{{ $manga->title }}"
                                    onerror="this.src='https://placehold.co/250x300';">
                                <div class="manga-info">
                                    <h5 class="manga-title">{{ $manga->title }}</h5>
                                    <span class="manga-genre">
                                        {{ $manga->genres->pluck('name')->join(', ') }}
                                    </span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


        <div class="comments bg-body-tertiary mt-4 py-1 px-3 rounded">
            <h1 class="fs-4 mb-3 fw-bold mt-2">Komentar</h1>
            @include('layouts.partials.disqus-comment')
        </div>
    </div>
    <div id="scrollProgressBar">
    </div>
@endsection
@push('js')
    @vite('resources/js/reader.js')
    <script>
        document.getElementById("report-error").addEventListener("click", function() {
            const url = window.location.href;
            const button = this;
            const reportedKey = "reported_" + btoa(url);

            if (sessionStorage.getItem(reportedKey)) {
                alert("Kamu sudah melaporkan error di chapter ini. Tunggu beberapa saat sebelum melapor lagi.");
                return;
            }

            if (confirm("Yakin mau lapor error di chapter ini?")) {
                button.disabled = true;

                fetch("{{ route('report.chapter') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            url: url,
                            desc: "Gambar tidak lengkap atau tidak muncul.",
                            honeypot: ""
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        sessionStorage.setItem(reportedKey, "true");
                        setTimeout(() => {
                            button.disabled = false;
                            sessionStorage.removeItem(reportedKey);
                        }, 600000);
                    })
                    .catch(() => {
                        alert("Gagal mengirim laporan. Coba lagi nanti.");
                        button.disabled = false;
                    });
            }
        });
    </script>
@endpush
