@extends('layouts.app')
@push('css')
    <style>
        .cover-img {
            object-fit: cover;
            height: 235px;
            width: 160px;
        }

        .also-read-img {
            object-fit: cover;
            object-position: center;
            height: 130px;
            width: 100%;
        }

        .genre-text {
            font-size: 14px;
        }

        @media (min-width: 768px) {
            .cover-img {
                width: 100%;
            }
        }

        @media (max-width: 767px) {
            .watch-now-btn {
                width: 100%;
            }

            .genre-text {
                font-size: 13px;
            }
        }

        @media (min-width: 768px) {
            .custom-full-width {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="bg-body-tertiary py-3 py-md-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-2 mb-3 mb-md-0 text-center">
                    <img src="{{ $manga->detail->cover }}" alt="{{ $manga->title }}"
                        class="img-fluid rounded shadow-sm cover-img">
                    <div class="mt-3">
                        <button class="btn bg-primary custom-full-width"><i
                                class="bi bi-bookmark-fill me-2"></i>Bookmark</button>
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <h2 class="h4 mb-3">{{ $manga->title }}</h2>
                    <p class="text-muted">{{ $manga->detail->synopsis }}</p>
                    <ul class="list-unstyled d-flex flex-wrap mb-0">
                        @foreach ($manga->genres as $genre)
                            @php
                                $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger'];
                                $color = $colors[$loop->index % 5];
                            @endphp
                            <li
                                class="me-3 mt-2 mb-1 {{ $color }} {{ $color }} bg-opacity-10 rounded-3 px-2 py-1">
                                {{ $genre->name }}</li>
                        @endforeach
                    </ul>
                    <div class="d-flex mt-3">
                        @if ($manga->chapters->isNotEmpty())
                            <a href="{{ route('manga.reader', [$manga->slug, $manga->chapters->first()->slug]) }}"
                                class="btn bg-primary me-2 watch-now-btn">Chapter 1</a>
                            <a href="{{ route('manga.reader', [$manga->slug, $manga->chapters->last()->slug]) }}"
                                class="btn bg-primary me-2 watch-now-btn">Last Chapter</a>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong>Type:</strong> <span
                                        class="text-muted">{{ $manga->detail->type ?? '-' }}</span></li>
                                <li class="mb-2"><strong>Author:</strong> <span
                                        class="text-muted">{{ $manga->detail->author ?? '-' }}</span></li>
                                <li class="mb-2"><strong>Artist:</strong> <span
                                        class="text-muted">{{ $manga->detail->artist ?? '-' }}</span></li>
                                <li class="mb-2"><strong>Year:</strong> <span
                                        class="text-muted">{{ $manga->detail->release_year ?? '-' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Status:</strong>
                                    <span
                                        class="badge 
                                        {{ $manga->detail->status === 'Ongoing'
                                            ? 'bg-primary'
                                            : ($manga->detail->status === 'Completed'
                                                ? 'bg-success'
                                                : ($manga->detail->status === 'Dropped'
                                                    ? 'bg-danger'
                                                    : 'bg-secondary')) }}">
                                        {{ $manga->detail->status ?? '-' }}
                                    </span>
                                </li>
                                </li>
                                <li class="mb-2">
                                    <strong>Rating:</strong>
                                    <span class="text-warning">
                                        ★★★★<span class="text-muted">★</span>
                                    </span>
                                    <small class="text-muted">(4.5)</small>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row mt-4">
            <div>
                <h1 class="fs-4 mb-3 fw-bold">Chapter List</h1>
            </div>
            <div class="col-12 col-md-8">
                <input type="text" id="chapterSearch" class="form-control mb-3" placeholder="Search Chapter...">
                <div class="chapter-list border rounded p-3" style="max-height: 450px; overflow-y: auto;">
                    <div class="row g-2" id="chapterContainer">
                        @foreach ($manga->chapters as $chapter)
                            <div class="col-6 col-md-3">
                                <a href="{{ route('manga.reader', [$manga->slug, $chapter->slug]) }}"
                                    class="btn border w-100">
                                    {{ $chapter->title }}
                                    <br>
                                    <small class="small text-muted">{{ $chapter->created_at->format('d M Y') }}</small>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="comments">
                    <h1 class="fs-4 mb-3 fw-bold mt-3">Komentar</h1>
                    <div></div>
                </div>
            </div>

            <div class="col-12 col-md-4 mt-4 mt-md-0">
                <h1 class="fs-4 mb-3 fw-bold">Baca Juga</h1>
                @foreach ($alsoRead as $item)
                    <div class="card mb-1">
                        <div class="row g-0">
                            <div class="col-4">
                                <a href="{{ route('manga.show', $item->slug) }}">
                                    <img src="{{ $item->cover }}" class="img-fluid rounded-start also-read-img"
                                        alt="{{ $item->title }}"
                                        onerror="this.onerror=null;this.src='{{ asset('assets/img/no-image.png') }}';">
                                </a>
                            </div>
                            <div class="col-8 d-flex align-items-center">
                                <div class="card-body">
                                    <h5 class="card-title m-0"><a href="{{ route('manga.show', $item->slug) }}"
                                            class="text-decoration-none fs-6">{{ Str::limit($item->title, 40, '...') }}</a>
                                    </h5>
                                    <p class="card-text genre-text m-0">
                                        <small class="text-secondary">Genres:</small>&nbsp;
                                        <small class="text-light">
                                            @foreach ($item->genres as $genre)
                                                {{ $genre->name . ', ' }}
                                            @endforeach
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        document.getElementById('chapterSearch').addEventListener('keyup', function() {
            let searchValue = this.value.toLowerCase();
            let chapters = document.querySelectorAll('#chapterContainer .col-6');

            chapters.forEach(chapter => {
                let text = chapter.textContent.toLowerCase();
                chapter.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });
    </script>
@endpush
