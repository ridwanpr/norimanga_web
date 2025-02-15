@extends('layouts.app')

@section('content')
    <div class="container">
        @auth
            <div class="row mb-2">
                <div class="col-12">
                    <h2 class="fs-4 fw-bold"><span class="text-primary">My </span>Bookmark </h2>
                </div>
            </div>
            @if ($bookmarks->isEmpty())
                <div class="alert alert-warning">Anda belum memiliki bookmark.</div>
            @else
                <div class="row">
                    @foreach ($bookmarks as $bookmark)
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <img src="{{ $bookmark->manga->cover_image ?? 'default-cover.jpg' }}" class="card-img-top"
                                    alt="{{ $bookmark->manga->title ?? 'Manga Tidak Diketahui' }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $bookmark->manga->title ?? 'Judul Tidak Diketahui' }}</h5>
                                    <a href="{{ route('manga.show', $bookmark->manga->id ?? '#') }}"
                                        class="btn btn-primary">Lihat</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="text-center">
                <h3>Anda belum login</h3>
                <p>Silakan login untuk melihat bookmark Anda.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary">Daftar</a>
            </div>
        @endauth
    </div>
@endsection
