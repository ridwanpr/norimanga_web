@extends('layouts.app')
@push('css')
    <style>
        .cover-img {
            object-fit: cover;
            height: 235px;
        }

        @media (max-width: 767px) {
            .watch-now-btn {
                width: 100%;
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
    <div class="bg-body-tertiary py-5">
        <div class="container">
            <div class="row">
                <!-- Image Column -->
                <div class="col-12 col-md-2 mb-3 mb-md-0 text-center">
                    <img src="https://placehold.co/250x300" alt="Manga cover" class="img-fluid rounded shadow-sm cover-img">
                    <div class="mt-3">
                        <button class="btn bg-primary custom-full-width">Bookmark</button>
                    </div>
                </div>

                <!-- Synopsis Column -->
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <h2 class="h4 mb-3">Synopsis</h2>
                    <p class="text-muted">Lorem ipsum dolor sit amet consectetur adipisicing elit. Itaque, harum natus?
                        Deleniti
                        dolorem assumenda dicta, nisi consectetur eos at nihil! Voluptatibus, quod expedita. Quisquam,
                        voluptatum.</p>
                    <ul class="list-unstyled d-flex flex-wrap mb-0">
                        <li class="me-3 text-primary bg-primary bg-opacity-10 rounded-3 px-2 py-1">Action</li>
                        <li class="me-3 text-success bg-success bg-opacity-10 rounded-3 px-2 py-1">Adventure</li>
                        <li class="me-3 text-warning bg-warning bg-opacity-10 rounded-3 px-2 py-1">Comedy</li>
                    </ul>
                    <div class="d-flex mt-3">
                        <a href="#" class="btn bg-primary me-2 watch-now-btn">Chapter 1</a>
                        <a href="#" class="btn bg-primary me-2 watch-now-btn">Last Chapter</a>
                    </div>
                </div>

                <!-- Details Column -->
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h1 class="card-title h3 mb-4">Manga Title</h1>
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong>Type:</strong> <span class="text-muted">Manga</span></li>
                                <li class="mb-2"><strong>Author:</strong> <span class="text-muted">John Doe</span></li>
                                <li class="mb-2"><strong>Artist:</strong> <span class="text-muted">John Doe</span></li>
                                <li class="mb-2"><strong>Year:</strong> <span class="text-muted">2024</span>
                                </li>
                                <li class="mb-2"><strong>Status:</strong> <span class="badge bg-success">Ongoing</span>
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
@endsection
