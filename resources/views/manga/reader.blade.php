@extends('layouts.app')
@push('css')
    <style>
        .breadcrumb-item,
        .breadcrumb-item a {
            text-transform: capitalize;
            font-size: 13px;
        }
        .reader-img {
            width: 100%;
        }
        @media (min-width: 768px) {
            .breadcrumb-item,
            .breadcrumb-item a {
                font-size: 13px;
            }

            .reader-img {
                width: 800px;
            }
        }
        #scrollProgressBar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 0;
            height: 5px;
            background: #007bff;
            transition: width 0.1s linear;
            z-index: 9999;
        }
        img:hover {
            filter: none;
            transform: none;
            opacity: 1;
        }
    </style>
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
                Baca manga terbaru {{ $chapter->manga->title }} ID {{ $chapter->title }} di Nori. Komik manga
                {{ $chapter->manga->title }} Bahas Indo selalu
                diperbarui di Nori.my. Jangan lupa untuk membaca pembaruan komik lainnya. Daftar koleksi komik Nori ada
                di menu Daftar Komik.
            </p>
            <div class="alert alert-warning w-100" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-warning"></i> <strong>Perhatian!</strong> Jika
                menemukan gambar yang error, rusak atau tidak tampil. <a href="#" class="alert-link">Laporkan</a>
                kepada kami.
            </div>
        </div>
        <div class="nav-ch-section mt-4">
            <div class="d-flex justify-content-between gap-2">
                <a href="#" class="btn btn-grey">
                    <i class="bi bi-chevron-left"> Prev</i>
                </a>
                <div class="d-flex gap-2 justify-content-between">
                    <a href="{{ route('manga.show', $chapter->manga->slug) }}" class="btn btn-grey">
                        <i class="bi bi-list"></i>
                    </a>
                    <a href="javascript:void(0)" class="btn btn-grey setting-btn">
                        <i class="bi bi-gear"></i>
                    </a>
                </div>
                <a href="#" class="btn btn-grey">
                    <i class="bi bi-chevron-right"> Next</i>
                </a>
            </div>
        </div>
    </div>
    <div class="container p-0 px-md-2">
        <div class="reader mt-3 mt-md-4" id="reader">
            <div class="reader-container d-flex flex-column justify-content-center align-items-center reader-img">
                @foreach ($images as $index => $image)
                    <img src="{{ $image }}" class="img-fluid"
                        alt="{{ $chapter->manga->title }} {{ $chapter->title }}"
                        onerror="this.onerror=null;this.src='{{ asset('assets/img/no-image.png') }}'"
                        @if ($index > 2) loading="lazy" @endif>
                @endforeach
            </div>
        </div>
    </div>
    <div class="container nav-bottom mt-4">
        <div class="nav-ch-section mt-4">
            <div class="d-flex justify-content-between gap-2">
                <a href="#" class="btn btn-grey">
                    <i class="bi bi-chevron-left"> Prev</i>
                </a>
                <div class="d-flex gap-2 justify-content-between">
                    <a href="{{ route('manga.show', $chapter->manga->slug) }}" class="btn btn-grey">
                        <i class="bi bi-list"></i>
                    </a>
                </div>
                <a href="#" class="btn btn-grey">
                    <i class="bi bi-chevron-right"> Next</i>
                </a>
            </div>
        </div>
        <div class="tags mt-4 py-0 px-1 bg-dark rounded">
            <small class="text-light">Tags: baca manga {{ $chapter->manga->title }} {{ $chapter->title }} bahasa
                Indonesia,
                komik {{ $chapter->manga->title }} {{ $chapter->title }} bahasa Indonesia, baca
                {{ $chapter->manga->title }} {{ $chapter->title }} online, {{ $chapter->manga->title }}
                {{ $chapter->title }} bab, {{ $chapter->manga->title }} {{ $chapter->title }} chapter,
                {{ $chapter->manga->title }} {{ $chapter->title }} high quality, {{ $chapter->manga->title }}
                {{ $chapter->title }} manga scan, {{ now()->format('F d, Y') }}, Nori.my</small>
        </div>
        <div class="comments mt-4 pb-1 pt-0 px-2 bg-dark rounded">
            <h1 class="fs-4 mb-3 fw-bold mt-3">Komentar</h1>
            <div class="disqus">
                Disqus here..
            </div>
        </div>
    </div>
    <div id="scrollProgressBar">
    </div>
@endsection
@push('js')
    @vite('resources/js/reader.js')
@endpush
