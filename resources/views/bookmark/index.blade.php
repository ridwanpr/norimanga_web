@extends('layouts.app')
@section('title', 'Bookmark - Baca Manga, Manhwa, Manhua Bahasa Indonesia - Nori')
@push('css')
    <style>
        .img-manga {
            width: 60px;
            height: 80px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .bookmark-title {
            font-size: 16px;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
            max-width: 500px;
        }

        @media (max-width: 768px) {
            .bookmark-title {
                font-size: 14px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: block;
                max-width: 190px;
            }

            .bookmark-btn {
                padding: 2px 6px;
                font-size: 0.85rem;
            }
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
                <div class="row g-2">
                    @foreach ($bookmarks as $bookmark)
                        <div class="col-md-6">
                            <div class="list-group-item border-bottom px-0">
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('manga.show', $bookmark->manga->slug) }}"
                                        class="d-flex align-items-center text-light text-decoration-none">
                                        <div class="me-3">
                                            <img src="{{ str_replace('.s3.tebi.io', '', $bookmark->manga->detail->cover) }}"
                                                alt="{{ $bookmark->manga->title }}" class="rounded img-manga">
                                        </div>

                                        <div class="flex-grow-1">
                                            <span class="bookmark-title" title="{{ $bookmark->manga->title }}">
                                                {{ $bookmark->manga->title }}
                                            </span>
                                            <table style="font-size: 12px;">
                                                <tr>
                                                    <td style="padding-right: 10px;">Chapter Terakhir:</td>
                                                    <td>{{ $bookmark->manga->lastChapter->chapter_number }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-right: 10px;">Chapter Dibaca:</td>
                                                    <td>
                                                        {{ $bookmark->manga->lastReadChapter ? ' ' . $bookmark->manga->lastReadChapter->chapter_number : 'Belum Dibaca' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </a>

                                    <div class="ms-auto">
                                        <form action="{{ route('bookmark.destroy') }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this bookmark?')">
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
                            </div>
                        </div>
                    @endforeach

                </div>

                <div class="d-flex justify-content-center mt-4">
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
