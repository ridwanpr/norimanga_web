@extends('layouts.app')

@section('content')
    <div class="container">
        @include('backend.partials.nav-admin')

        <div class="container">
            <div class="row mt-4">
                <div class="col-12 my-2 mb-3">
                    <div class="card p-2 shadow-sm text-center">
                        <h5 class="fw-bold">Auto Fetch Komik & Chapter</h5>
                        <div class="d-flex flex-wrap justify-content-center mt-1">
                            @php
                                $sources = [
                                    ['name' => 'WestManga', 'url' => 'westmanga.fun', 'color' => '#dc3545'],
                                    ['name' => 'ManhwaIndo', 'url' => 'manhwaindo.one', 'color' => '#6f42c1'],
                                    ['name' => 'Comicaso', 'url' => 'comicaso.id', 'color' => '#20c997'],
                                    ['name' => 'ManhwaID', 'url' => 'manhwaid.id', 'color' => '#0d6efd'],
                                    ['name' => 'Kiryuu', 'url' => 'kiryuu01.com', 'color' => '#6e4f9d'],
                                    ['name' => 'Komikindo', 'url' => 'komikindo2.com', 'color' => '#007aff'],
                                    ['name' => 'Maid', 'url' => 'maid.my.id', 'color' => '#3a6595'],
                                    ['name' => 'Komiksin', 'url' => 'komiksin.id', 'color' => '#c21b0a'],
                                ];
                            @endphp
                            @foreach ($sources as $source)
                                <div class="p-1 m-1 flex-fill text-white rounded text-center"
                                    style="background-color: {{ $source['color'] }}; min-width: 10%;">
                                    <strong>{{ $source['name'] }}</strong><br>
                                    <span class="small">{{ $source['url'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-5 mt-2 mt-md-0">
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
                                        @foreach (\App\Helpers\Bucket::all() as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-grey">
                                    <i class="bi bi-cloud-download"></i> Fetch
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-5 mt-2 mt-md-0">
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
                                        @foreach (\App\Helpers\Bucket::all() as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="manga_id" id="manga-id">
                                    <div id="manga-results" class="manga-results dropdown-menu show w-100"></div>
                                </div>
                                <button type="submit" class="btn btn-grey">
                                    <i class="bi bi-cloud-download"></i> Fetch
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-2 mt-2 mt-md-0">
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                            <span>Auto Single Chapter</span>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('automation.fetch.chapter-image') }}" method="POST">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-12 col-md">
                                        <input type="text" name="search_manga" id="search-manga-chapter"
                                            class="form-control" placeholder="Search manga">
                                    </div>
                                    <div class="col-12 col-md">
                                        <input type="text" name="chapter_url" id="chapter_url" class="form-control"
                                            placeholder="Chapter url">
                                    </div>
                                    <div class="col-12 col-md">
                                        <input type="text" name="chapter_title" id="chapter_title" class="form-control"
                                            placeholder="Chapter title">
                                    </div>
                                    <div class="col-12 col-md">
                                        <input type="text" name="chapter_number" id="chapter_number" class="form-control"
                                            placeholder="Chapter number">
                                    </div>
                                    <div class="col-12 col-md">
                                        <select name="bucket" class="form-select">
                                            <option value="">Select Bucket</option>
                                            @foreach (\App\Helpers\Bucket::all() as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="manga_id" id="manga-id-chapter">
                                    <div id="manga-results-chapter" class="manga-results dropdown-menu show w-100"></div>
                                    <div class="col-12 col-md-auto">
                                        <button type="submit" class="btn btn-grey w-100">
                                            <i class="bi bi-cloud-download"></i> Fetch
                                        </button>
                                    </div>
                                </div>
                            </form>
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
                            <span>Latest Chapter</span>
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
        .manga-results {
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
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".manga-results").forEach(resultsContainer => {
                resultsContainer.style.display = "none";
            });
        });

        function debounce(func, delay) {
            let timer;
            return function() {
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(this, arguments), delay);
            };
        }

        function searchManga(inputId, resultsId, mangaIdField) {
            let inputElement = document.getElementById(inputId);
            let resultsContainer = document.getElementById(resultsId);
            let mangaIdElement = document.getElementById(mangaIdField);

            if (!resultsContainer || !mangaIdElement) {
                console.error(`Error: Missing element. resultsContainer=${resultsId}, mangaIdElement=${mangaIdField}`);
                return;
            }

            if (inputElement.value.length > 2) {
                fetch("{{ route('automation.chapter.search') }}?query=" + encodeURIComponent(inputElement.value))
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Network response was not ok");
                        }
                        return response.json();
                    })
                    .then(data => {
                        resultsContainer.innerHTML = "";
                        if (data.length > 0) {
                            resultsContainer.style.display = "block";
                            data.forEach(manga => {
                                let item = document.createElement("div");
                                item.textContent = manga.title;
                                item.classList.add("manga-item");
                                item.addEventListener("click", function() {
                                    inputElement.value = manga.title;
                                    mangaIdElement.value = manga.id;
                                    resultsContainer.style.display = "none";
                                });
                                resultsContainer.appendChild(item);
                            });
                        } else {
                            resultsContainer.style.display = "none";
                        }
                    })
                    .catch(error => console.error("Error fetching manga:", error));
            } else {
                resultsContainer.style.display = "none";
            }
        }


        document.getElementById('search-manga').addEventListener('keyup', debounce(() => searchManga('search-manga',
            'manga-results', 'manga-id'), 300));
        document.getElementById('search-manga-chapter').addEventListener('keyup', debounce(() => searchManga(
            'search-manga-chapter', 'manga-results-chapter', 'manga-id-chapter'), 300));

        document.addEventListener('click', function(event) {
            document.querySelectorAll('.manga-results').forEach(resultsContainer => {
                if (!resultsContainer.previousElementSibling.contains(event.target) && !resultsContainer
                    .contains(event.target)) {
                    resultsContainer.style.display = "none";
                }
            });
        });
    </script>
@endpush
