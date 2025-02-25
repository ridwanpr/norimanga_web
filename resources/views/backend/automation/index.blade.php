@extends('layouts.app')

@section('content')
    <div class="container">
        @include('backend.partials.nav-admin')

        <div class="container">
            <div class="row mt-4">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title fs-6 fw-bold">Auto Fetch</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('automation.fetch.manga') }}" method="POST">
                                @csrf
                                <div class="mb-3 position-relative">
                                    <input type="text" class="form-control me-1" name="url"
                                        placeholder="Input manga url">
                                </div>
                                <button type="submit" class="btn btn-grey">
                                    <i class="bi bi-cloud-download"></i> Fetch
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title fs-6 fw-bold">Auto Chapter</h5>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST">
                                @csrf
                                <div class="mb-3 position-relative">
                                    <input type="text" id="search-manga" class="form-control" placeholder="Search manga">
                                    <div id="manga-results" class="dropdown-menu show w-100"></div>
                                </div>
                                <button type="submit" class="btn btn-grey">
                                    <i class="bi bi-cloud-download"></i> Fetch
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title fs-6 fw-bold">Latest Manga</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Title</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($latestManga as $manga)
                                            <tr>
                                                <td>{{ $manga->title }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title fs-6 fw-bold">Latest Chapter</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Title</th>
                                            <th scope="col">Chapter</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($latestChapter as $chapter)
                                            <tr>
                                                <td>{{ $chapter->manga->title }}</td>
                                                <td>{{ $chapter->chapter_number }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        #manga-results {
            background: var(--bs-card-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: var(--bs-btn-border-radius);
            overflow: hidden;
            display: none;
            max-height: 250px;
            overflow-y: auto;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
        }

        .manga-item {
            padding: 10px 15px;
            cursor: pointer;
            transition: background 0.2s;
            color: var(--bs-body-color);
        }

        .manga-item:hover {
            background: var(--bs-secondary);
            color: var(--bs-light);
        }

        .manga-item:active {
            background: var(--bs-primary);
            color: white;
        }
    </style>
@endpush

@push('js')
    <script>
        function debounce(func, delay) {
            let timer;
            return function() {
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(this, arguments), delay);
            };
        }

        function searchManga() {
            let query = document.getElementById('search-manga').value;
            let resultsContainer = document.getElementById('manga-results');

            if (query.length > 2) {
                fetch("{{ route('automation.chapter.search') }}?query=" + encodeURIComponent(query))
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        resultsContainer.innerHTML = "";
                        resultsContainer.style.display = "block";
                        if (data.length > 0) {
                            data.forEach(manga => {
                                let item = document.createElement('div');
                                item.textContent = manga.title;
                                item.classList.add('manga-item');
                                item.addEventListener('click', function() {
                                    document.getElementById('search-manga').value = manga.title;
                                    resultsContainer.style.display = "none";
                                });
                                resultsContainer.appendChild(item);
                            });
                        } else {
                            resultsContainer.innerHTML = `<div class="manga-item">No results found</div>`;
                        }
                    })
                    .catch(error => console.error('Error fetching manga:', error));
            } else {
                resultsContainer.style.display = "none";
            }
        }

        document.getElementById('search-manga').addEventListener('keyup', debounce(searchManga, 300));

        document.addEventListener('click', function(event) {
            let resultsContainer = document.getElementById('manga-results');
            if (!document.getElementById('search-manga').contains(event.target) && !resultsContainer.contains(event
                    .target)) {
                resultsContainer.style.display = "none";
            }
        });
    </script>
@endpush
