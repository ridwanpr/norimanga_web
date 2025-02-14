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
    <div class="bg-body-tertiary py-3 py-md-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-2 mb-3 mb-md-0 text-center">
                    <img src="https://placehold.co/250x300" alt="Manga cover" class="img-fluid rounded shadow-sm cover-img">
                    <div class="mt-3">
                        <button class="btn bg-primary custom-full-width"><i
                                class="bi bi-bookmark-fill me-2"></i>Bookmark</button>
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <h2 class="h4 mb-3">Lorem ipsum dolor sit amet consectetur adipisicing elit.</h2>
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
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
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
    <div class="container">
        <div class="row mt-4">
            <div>
                <h1 class="fs-4 mb-3 fw-bold">Chapter List</h1>
            </div>
            <div class="col-12 col-md-8">
                <input type="text" id="chapterSearch" class="form-control mb-3" placeholder="Search Chapter...">
                <div class="chapter-list border rounded p-3" style="max-height: 450px; overflow-y: auto;">
                    <div class="row g-2" id="chapterContainer">
                        @for ($i = 1; $i <= 100; $i++)
                            <div class="col-6 col-md-3">
                                <a href="#" class="btn border w-100">Chapter {{ $i }}</a>
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="comments">
                    <h1 class="fs-4 mb-3 fw-bold mt-3">Komentar</h1>
                    <div></div>
                </div>
            </div>

            <div class="col-12 col-md-4 mt-4 mt-md-0">
                <h1 class="fs-4 mb-3 fw-bold">Baca Juga</h1>
                <div class="card mb-1">
                    <div class="row g-0">
                        <div class="col-4">
                            <img src="https://placehold.co/500x600" class="img-fluid rounded-start fixed-size-trending"
                                alt="Manga title">
                        </div>
                        <div class="col-8 d-flex align-items-center">
                            <div class="card-body">
                                <h5 class="card-title m-0">Manga Title</h5>
                                <p class="card-text m-0">
                                    <small class="text-secondary">Genres:</small>&nbsp;
                                    <small class="text-light">Action, Adventure, Comedy</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-1">
                    <div class="row g-0">
                        <div class="col-4">
                            <img src="https://placehold.co/500x600" class="img-fluid rounded-start fixed-size-trending"
                                alt="Manga title">
                        </div>
                        <div class="col-8 d-flex align-items-center">
                            <div class="card-body">
                                <h5 class="card-title m-0">Manga Title</h5>
                                <p class="card-text m-0">
                                    <small class="text-secondary">Genres:</small>&nbsp;
                                    <small class="text-light">Action, Adventure, Comedy</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-1">
                    <div class="row g-0">
                        <div class="col-4">
                            <img src="https://placehold.co/500x600" class="img-fluid rounded-start fixed-size-trending"
                                alt="Manga title">
                        </div>
                        <div class="col-8 d-flex align-items-center">
                            <div class="card-body">
                                <h5 class="card-title m-0">Manga Title</h5>
                                <p class="card-text m-0">
                                    <small class="text-secondary">Genres:</small>&nbsp;
                                    <small class="text-light">Action, Adventure, Comedy</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-1">
                    <div class="row g-0">
                        <div class="col-4">
                            <img src="https://placehold.co/500x600" class="img-fluid rounded-start fixed-size-trending"
                                alt="Manga title">
                        </div>
                        <div class="col-8 d-flex align-items-center">
                            <div class="card-body">
                                <h5 class="card-title m-0">Manga Title</h5>
                                <p class="card-text m-0">
                                    <small class="text-secondary">Genres:</small>&nbsp;
                                    <small class="text-light">Action, Adventure, Comedy</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
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
