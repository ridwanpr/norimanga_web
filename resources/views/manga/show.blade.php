@extends('layouts.app')
@section('meta')
    <meta name="description"
        content="Baca Komik {{ $manga->detail->type }} {{ $manga->title }} di Panelesia! Terjemahan Bahasa Indonesia, dan update tercepat gratis. {{ $manga->detail->type }} {{ $manga->title }} selalu diupdate di Panelesia.">
    <meta property="og:title" content="{{ $manga->title }} - Read Online Free">
    <meta property="og:description" content="{{ Str::limit($manga->detail->synopsis, 150) }}">
    <meta property="og:image" content="{{ $manga->detail->cover }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
@endsection
@section('title', "$manga->title Bahasa Indonesia - Panelesia - Manga Indonesia")
@push('css')
    @vite('resources/css/manga-show.css')
@endpush

@section('content')
    <div class="bg-body-tertiary py-3 py-md-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-2 mb-3 mb-md-0 text-center">
                    <img src="{{ $manga->detail->cover }}" alt="{{ $manga->title }}"
                        class="img-fluid rounded shadow-sm cover-img">
                    <div class="mt-3">
                        @if ($isBookmarked)
                            <button class="btn bg-danger custom-full-width bookmark-btn" data-id="{{ $manga->id }}">
                                <i class="bi bi-bookmark-fill me-2"></i>
                                <span>Bookmarked</span>
                            </button>
                        @else
                            @auth
                                <button class="btn bg-success custom-full-width bookmark-btn" data-id="{{ $manga->id }}">
                                    <i class="bi bi-bookmark-fill me-2"></i>
                                    <span>Bookmark</span>
                                </button>
                            @else
                                <a href="{{ route('bookmark.index') }}" class="btn bg-success custom-full-width">
                                    <i class="bi bi-bookmark-fill me-2"></i>
                                    <span>Bookmark</span>
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <h1 class="h4 mb-3">{{ $manga->title }}</h1>
                    <p class="text-muted">{{ $manga->detail->synopsis }}</p>
                    <ul class="genre-tags d-flex flex-wrap gap-2 mb-0">
                        @foreach ($manga->genres as $genre)
                            <li class="genre-tag">
                                <span class="text-capitalize">{{ $genre->name }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="d-flex mt-3">
                        @if ($manga->chapters->isNotEmpty())
                            <a href="{{ route('manga.reader', [$manga->slug, $manga->firstChapter->slug]) }}"
                                class="btn btn-secondary me-2 border watch-now-btn">First Chapter</a>
                            <a href="{{ route('manga.reader', [$manga->slug, $manga->lastChapter->slug]) }}"
                                class="btn btn-secondary me-2 border watch-now-btn">Last Chapter</a>
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
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row mt-4">
            <div class="col-12 col-md-8">
                <div class="latest-read-wrapper mt-3 mb-3">
                    <h2 class="border-start border-3 border-primary ps-2 mb-2 fs-4 mb-3 fw-bold">Riwayat Baca</h2>
                    <div class="list-group list-group-flush">
                        @forelse ($latestReadChapter as $latestCh)
                            <a href="{{ route('manga.reader', [$latestCh->manga->slug, $latestCh->chapter->slug]) }}"
                                class="list-group-item list-group-item-action py-2 px-0 d-flex align-items-center border-bottom">
                                <div class="d-flex flex-column">
                                    <span class="small fw-medium text-truncate">{{ $latestCh->chapter->title }}</span>
                                    <span class="d-flex align-items-center">
                                        <small class="text-muted">{{ $latestCh->created_at->diffForHumans() }}</small>
                                    </span>
                                </div>
                                <i class="bi bi-chevron-right ms-auto fs-6 text-muted"></i>
                            </a>
                        @empty
                            <div class="small text-muted py-2">
                                <i class="bi bi-book-half me-1"></i>@auth
                                No reading history
                                @else
                                <a href="{{ route('login') }}" class="text-decoration-none">Login</a> untuk menyimpan riwayat baca.
                                @endauth
                            </div>
                        @endforelse
                    </div>
                </div>

                <div>
                    <h2 class="fs-4 mb-3 mt-2 fw-bold border-start border-3 border-primary ps-2 ">Chapter List</h2>
                </div>
                <input type="text" id="chapterSearch" class="form-control mb-3" placeholder="Search Chapter...">
                <div class="chapter-list border rounded p-3" style="max-height: 420px; overflow-y: auto;">
                    <div class="row g-2" id="chapterContainer">
                        @foreach ($sortedChapters as $chapter)
                            <div class="col-6 col-md-3">
                                <a href="{{ route('manga.reader', [$manga->slug, $chapter->slug]) }}"
                                    class="btn border w-100 position-relative {{ in_array($chapter->id, $readChapters ?? []) ? 'read-chapter' : '' }}">
                                    {{ $chapter->title }}
                                    <br>
                                    <small class="small text-muted">{{ $chapter->created_at->format('d M Y') }}</small>

                                    @if (in_array($chapter->id, $readChapters ?? []))
                                        <span class="position-absolute top-0 end-0 p-1">
                                            <i class="bi bi-check-circle-fill text-success small"></i>
                                        </span>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="comments bg-body-tertiary mt-4 py-1 px-3 rounded">
                    <h2 class="fs-4 mb-3 fw-bold mt-2">Komentar</h2>
                    @include('layouts.partials.disqus-comment')
                </div>
            </div>

            <div class="col-12 col-md-4 mt-4 mt-md-0">
                <h2 class="fs-4 mb-3 fw-bold border-start border-3 border-primary ps-2 ">Baca Juga</h2>
                @foreach ($alsoRead as $item)
                    <a href="{{ route('manga.show', $item->slug) }}"
                        class="card also-read-wrapper mb-1 text-decoration-none">
                        <div class="row g-0">
                            <div class="col-3 also-read-img-wrapper">
                                <img src="{{ $item->cover }}" class="rounded-start also-read-img"
                                    alt="{{ $item->title }}"
                                    onerror="this.onerror=null;this.src='{{ asset('assets/img/no-image.png') }}';">
                            </div>
                            <div class="col-9 d-flex align-items-center">
                                <div class="card-body px-3 py-1">
                                    <h5 class="card-title m-0 fs-6">{{ Str::limit($item->title, 48, '...') }}</h5>
                                    <p class="card-text genre-text m-0">
                                        <small class="text-light">
                                            @foreach (array_slice($item->genres->toArray(), 0, 8) as $index => $genreItem)
                                                {{ $genreItem['name'] }}{{ $index < count(array_slice($item->genres->toArray(), 0, 8)) - 1 ? ',' : '' }}
                                            @endforeach
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/axios@1.7.9/dist/axios.min.js"></script>
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
    @vite('resources/js/bookmark.js')
@endpush
