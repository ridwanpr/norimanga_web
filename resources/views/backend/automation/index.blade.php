@extends('layouts.app')

@section('content')
    <div class="container">
        @include('backend.partials.nav-admin')

        <div class="container">
            <div class="row mt-4">
                <div class="col-12 col-md-5">
                    <div class="card">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                            <span>Auto Fetch</span>

                        </div>
                        <div class="card-body">
                            <form action="{{ route('automation.fetch.manga') }}" method="POST">
                                @csrf
                                <div class="mb-3 position-relative">
                                    <input type="text" class="form-control me-1" name="url"
                                        placeholder="Input manga url">
                                    <select name="bucket" class="form-select mt-2">
                                        <option value="">Select Bucket</option>
                                        <option value="s1">s1</option>
                                        <option value="s2">s2</option>
                                        <option value="s3">s3</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-grey">
                                    <i class="bi bi-cloud-download"></i> Fetch
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-5">
                    <div class="card">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                            <span>Auto Chapter</span>

                        </div>
                        <div class="card-body">
                            <form action="{{ route('automation.fetch.chapter') }}" method="POST">
                                @csrf
                                <div class="mb-3 position-relative">
                                    <input type="text" name="search_manga" id="search-manga" class="form-control"
                                        placeholder="Search manga">
                                    <select name="bucket" class="form-select mt-2">
                                        <option value="">Select Bucket</option>
                                        <option value="s1">s1</option>
                                        <option value="s2">s2</option>
                                        <option value="s3">s3</option>
                                    </select>
                                    <input type="hidden" name="manga_id" id="manga-id">
                                    <div id="manga-results" class="dropdown-menu show w-100"></div>
                                </div>
                                <button type="submit" class="btn btn-grey">
                                    <i class="bi bi-cloud-download"></i> Fetch
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="card">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                            <span>Tools</span>

                        </div>
                        <div class="card-body">
                            <a href="{{ route('refresh-cache') }}" class="btn btn-warning"
                                onclick="return confirm('Are you sure you want to refresh the cache?');">
                                <i class="bi bi-arrow-repeat"></i> Refresh Cache
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                            <span>Latest Comic</span>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Title</th>
                                            <th scope="col">Fetched At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($latestManga as $manga)
                                            <tr>
                                                <td>{{ $manga->title }}</td>
                                                <td>{{ $manga->updated_at->format('d M Y H:i:s') }}</td>
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
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                            <span>Latest Chaoter</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Title</th>
                                            <th scope="col">Chapter</th>
                                            <th scope="col">Fetched At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($latestChapter as $chapter)
                                            <tr>
                                                <td>{{ $chapter->manga->title }}</td>
                                                <td>{{ $chapter->chapter_number }}</td>
                                                <td>{{ $chapter->updated_at->format('d M Y H:i:s') }}</td>
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
                                    document.getElementById('manga-id').value = manga.id;
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
