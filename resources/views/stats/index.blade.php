@extends('layouts.app')
@section('title', 'Riwayat Baca & Statistik - Panelesia')

@push('css')
    <style>
        .stat-card {
            background: #1e1e1e;
            border: none;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
        }

        .stat-card h5 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .stat-card p {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            color: #00b4d8;
        }

        .history-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #333;
        }

        .history-img {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 6px;
        }

        .history-content {
            flex-grow: 1;
            padding-left: 10px;
        }

        .history-title {
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            margin-bottom: 2px;
        }

        .history-meta {
            font-size: 12px;
            color: #aaa;
            display: block;
            margin-top: 2px;
        }

        @media (max-width: 576px) {
            .stat-card h5 {
                font-size: 12px;
            }

            .stat-card p {
                font-size: 16px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row g-1 mb-3">
            <div class="col-12">
                <h2 class="fs-5 fw-bold text-white"><span class="text-primary">Riwayat </span>& Statistik</h2>
            </div>
        </div>

        <div class="row g-2">
            <div class="col-6 col-md-6">
                <div class="stat-card">
                    <h5>Total Manga Dibaca</h5>
                    <p>{{ $totalManga }}</p>
                </div>
            </div>
            <div class="col-6 col-md-6">
                <div class="stat-card">
                    <h5>Total Chapter Dibaca</h5>
                    <p>{{ $totalChapters }}</p>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Perhatian!</strong> Riwayat baca memiliki delay hingga 10 menit.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold text-white fs-6">Riwayat Baca</h4>

                <div class="history-list">
                    @foreach ($userActivities as $userActivity)
                        <a href="{{ route('manga.show', $userActivity->manga->slug) }}" class="text-decoration-none">
                            <div class="history-item">
                                <img src="{{ $userActivity->manga->detail->cover }}" alt="Manga Cover" class="history-img">
                                <div class="history-content">
                                    <span class="history-title">{{ $userActivity->manga->title }}</span>
                                    <span class="history-meta">{{ $userActivity->chapter->title }}</span>
                                    <span class="history-meta">{{ $userActivity->created_at->format('d M Y H:i') }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="mt-3">
                {{ $userActivities->links() }}
            </div>
        </div>
    </div>
@endsection
