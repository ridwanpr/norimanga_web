@extends('layouts.app')
@section('title', 'Bookmark - Baca Manga, Manhwa, Manhua Bahasa Indonesia - Nori')
@push('css')
    <style>
        @media (max-width: 768px) {
            .img-manga {
                max-height: 80px;
            }

            .bookmark-btn {
                padding: 2px 6px;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 768px) {
            .bookmark-title {
                font-size: 14px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: block;
                max-width: 100%;
            }
        }

        .bookmark-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .bookmark-content {
            flex-grow: 1;
            padding-left: 10px;
        }

        .bookmark-title {
            font-size: 16px;
            font-weight: bold;
            display: block;
        }

        .bookmark-genres {
            font-size: 12px;
            color: #aaa;
            display: block;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        @auth
            <div class="row mb-3">
                <div class="col-12">
                    <h2 class="fs-4 fw-bold"><span class="text-primary">My </span>Bookmark</h2>
                </div>
            </div>

            @if ($bookmarks->isEmpty())
                <div class="alert alert-warning">Anda belum memiliki bookmark.</div>
            @else
                <ul class="list-group list-group-flush">
                    @foreach ($bookmarks as $bookmark)
                        <li class="list-group-item border-bottom px-0">
                            <a href="{{ route('manga.show', $bookmark->manga->slug) }}"
                                class="d-block w-100 text-decoration-none bookmark-item">
                                <div class="row align-items-center w-100">
                                    <div class="col-auto">
                                        <img src="{{ str_replace('.s3.tebi.io', '', $bookmark->manga->detail->cover) }}"
                                            alt="{{ $bookmark->manga->title }}" class="img-fluid rounded img-manga"
                                            style="width: 60px; height: auto;">
                                    </div>

                                    <div class="col d-flex flex-column justify-content-center bookmark-content">
                                        <span class="bookmark-title">{{ $bookmark->manga->title }}</span>
                                        <span class="bookmark-genres">
                                            {{ implode(', ', $bookmark->manga->genres->pluck('name')->toArray()) }}
                                        </span>
                                    </div>

                                    <div class="col-auto d-flex align-items-center">
                                        <form action="{{ route('bookmark.destroy') }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="manga_id" value="{{ $bookmark->manga->id }}">
                                            <button type="submit"
                                                class="btn btn-sm btn-outline-danger bookmark-btn d-flex align-items-center justify-content-center"
                                                style="width: 28px; height: 28px;">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="d-flex justify-content-center mt-3">
                    {{ $bookmarks->links() }}
                </div>


                <div class="d-flex justify-content-center mt-3">
                    {{ $bookmarks->links() }}
                </div>
            @endif
        @else
            <div class="text-center my-5">
                <h3>Anda belum login</h3>
                <p>Silakan login untuk melihat bookmark Anda.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary">Daftar</a>
            </div>
        @endauth
    </div>
@endsection
