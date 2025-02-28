@extends('layouts.app')
@section('title', 'Riwayat Baca & Statistik - Nori')

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
            font-size: 11px;
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
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <h5>Total Manga Dibaca</h5>
                    <p>0</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <h5>Total Chapter Dibaca</h5>
                    <p>0</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <h5>Jumlah Scroll</h5>
                    <p>0</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <h5>Total Waktu Membaca</h5>
                    <p>0 Menit</p>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <h4 class="fw-bold text-white fs-6">Riwayat Baca</h4>

                <div class="history-list">
                    <div class="history-item">
                        <img src="https://via.placeholder.com/50x70" alt="Manga Cover" class="history-img">
                        <div class="history-content">
                            <span class="history-title">Nama Manga</span>
                            <span class="history-meta">Chapter 1</span>
                            <span class="history-meta">Dibaca 2 menit lalu</span>
                        </div>
                    </div>
                    <div class="history-item">
                        <img src="https://via.placeholder.com/50x70" alt="Manga Cover" class="history-img">
                        <div class="history-content">
                            <span class="history-title">Nama Manga Lain</span>
                            <span class="history-meta">Chapter 10</span>
                            <span class="history-meta">Dibaca kemarin</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
