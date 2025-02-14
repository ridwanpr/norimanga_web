@extends('layouts.app')
@push('css')
    <style>
        .breadcrumb-item,
        .breadcrumb-item a {
            text-transform: capitalize;
            font-size: 13px;
        }

        @media (min-width: 768px) {

            .breadcrumb-item,
            .breadcrumb-item a {
                font-size: 13px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container py-3 py-md-5 pb-md-2">
        <div class="top-section">
            <div class="text-center mb-3">
                <h1 class="title fs-4 fw-bold">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis, explicabo?
                </h1>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-dark p-3 rounded-3 d-flex flex-wrap justify-content-center text-white">
                    <li class="breadcrumb-item">
                        <a href="#" class="text-white text-decoration-none">Beranda</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#" class="text-white text-decoration-none">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptate veritatis pariatur quia eum
                            nulla! Accusantium.
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-white" aria-current="page">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque, iure?
                    </li>
                </ol>
            </nav>
            <p>
                Baca manga terbaru [Judul] ID Chapter [No Ch] di Nori. Manga [Judul] ID selalu
                diperbarui di Nori.my. Jangan lupa untuk membaca pembaruan manga lainnya. Daftar koleksi manga Nori ada
                di menu Daftar Komik.
            </p>
        </div>
        <div class="nav-ch-section mt-4">
            <div class="d-flex justify-content-between gap-2">
                <a href="#" class="btn bg-primary">
                    <i class="bi bi-chevron-left"> Prev</i>
                </a>
                <div class="d-flex gap-2 justify-content-between">
                    <a href="#" class="btn bg-primary">
                        <i class="bi bi-list"></i>
                    </a>
                    <a href="javascript:void(0)" class="btn bg-primary">
                        <i class="bi bi-gear"></i>
                    </a>
                </div>
                <a href="#" class="btn bg-primary">
                    <i class="bi bi-chevron-right"> Next</i>
                </a>
            </div>
        </div>
    </div>
    <div class="container p-0 px-md-2">
        <div class="reader mt-2 mt-md-4" id="reader">
            <div class="reader-container d-flex flex-column align-items-center">
                <img src="https://placehold.co/500x600" class="img-fluid w-100" alt="">
                <img src="https://placehold.co/500x600" class="img-fluid w-100" alt="">
                <img src="https://placehold.co/500x600" class="img-fluid w-100" alt="">
                <img src="https://placehold.co/500x600" class="img-fluid w-100" alt="">
            </div>
        </div>
    </div>
    <button id="backToTop" class="btn btn-grey position-fixed" style="bottom: 20px; left: 20px; display: none;">
        <i class="bi bi-chevron-up"></i>
    </button>
@endsection
@push('js')
    @vite('resources/js/reader.js')
@endpush
