@extends('layouts.app')
@section('meta')
    <meta name="description"
        content="Jelajahi koleksi manga, manhwa, manhua di Nori.my! Cari komik favorit berdasarkan genre, tipe, atau tahun — tersedia dalam terjemahan Bahasa Indonesia berkualitas.">
@endsection
@section('title', 'Daftar Komik - Baca Manga, Manhwa, Manhua Bahasa Indonesia - Nori')
@section('content')
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between">
            <h1 class="fs-5 mb-3 fw-bold text-white">Daftar Komik</h1>
            <a href="{{ route('manga.grid-list') }}">Grid Mode</a>
        </div>

        <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
            <a href="#num" class="btn btn-sm btn-outline-secondary text-white px-2">#</a>
            @foreach (range('A', 'Z') as $letter)
                <a href="#{{ $letter }}"
                    class="btn btn-sm btn-outline-secondary text-white px-2">{{ $letter }}</a>
            @endforeach
        </div>

        <div class="comic-list">
            @foreach ($mangas as $letter => $titles)
                <div id="{{ $letter }}" class="mt-3">
                    <h2 class="fs-6 fw-bold border-bottom pb-1 text-primary">{{ $letter }}</h2>

                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <ul class="list-unstyled">
                                @foreach ($titles as $index => $manga)
                                    @if ($index % 2 == 0)
                                        <li class="border-bottom p-1">
                                            <a href="{{ route('manga.show', $manga->slug) }}"
                                                class="text-decoration-none text-white small">{{ $manga->title }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-12 col-lg-6">
                            <ul class="list-unstyled">
                                @foreach ($titles as $index => $manga)
                                    @if ($index % 2 != 0)
                                        <li class="border-bottom p-1">
                                            <a href="{{ route('manga.show', $manga->slug) }}"
                                                class="text-decoration-none text-white small">{{ $manga->title }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".btn-outline-secondary").forEach(button => {
                button.addEventListener("click", function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute("href").substring(1);
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 50,
                            behavior: "smooth"
                        });
                    }
                });
            });
        });
    </script>
@endpush
