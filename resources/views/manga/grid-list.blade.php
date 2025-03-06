@extends('layouts.app')
@section('meta')
    <meta name="description"
        content="Jelajahi koleksi manga, manhwa, manhua di Panelesia! Cari komik favorit berdasarkan genre, tipe, atau tahun — tersedia dalam terjemahan Bahasa Indonesia berkualitas.">
@endsection
@section('title', 'Daftar Komik - Baca Manga, Manhwa, Manhua Bahasa Indonesia - Panelesia')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @vite('resources/css/select2.css')
@endpush
@section('content')
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between">
            <h1 class="fs-5 mb-3 fw-bold text-white">Daftar Komik</h1>
            <a href="{{ route('manga.text-list') }}">Text Mode</a>
        </div>
        <div class="filtering mb-4">
            <form class="row row-cols-2 row-cols-md-auto g-2 align-items-center" method="GET"
                action="{{ route('manga.grid-list') }}">
                <div class="col">
                    <select name="genre" id="genre" class="form-select">
                        <option value="">Genre</option>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre->slug }}" {{ request('genre') == $genre->slug ? 'selected' : '' }}>
                                {{ $genre->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <select name="order_by" id="order_by" class="form-select">
                        <option value="latest" {{ request('order_by') == 'latest' ? 'selected' : '' }}>Latest
                        </option>
                        <option value="popular" {{ request('order_by') == 'popular' ? 'selected' : '' }}>Popular</option>
                    </select>
                </div>
                <div class="col">
                    <select name="type" id="type" class="form-select">
                        <option value="">&nbsp;Tipe</option>
                        <option value="manga" {{ request('type') == 'manga' ? 'selected' : '' }}>Manga</option>
                        <option value="manhwa" {{ request('type') == 'manhwa' ? 'selected' : '' }}>Manhwa</option>
                        <option value="manhua" {{ request('type') == 'manhua' ? 'selected' : '' }}>Manhua</option>
                    </select>
                </div>
                <div class="col">
                    <select name="status" id="status" class="form-select">
                        <option value="">&nbsp;Status</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                        </option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    </select>
                </div>
                <div class="col-12 col-md-auto">
                    <button type="submit" class="btn btn-grey w-100 w-md-auto">Filter</button>
                </div>
            </form>

        </div>
        <div class="row g-3">
            @foreach ($latestUpdate as $key => $manga)
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <a href="{{ route('manga.show', $manga->slug) }}" class="text-decoration-none">
                        <div class="image-container position-relative mb-1">
                            <img src="{{ $manga->cover }}" onerror="this.src='{{ asset('assets/img/no-image.png') }}'"
                                class="img-fluid rounded fixed-size-latest" alt="{{ $manga->title ?? '' }}">
                            <div class="image-title">
                                {{ \Str::limit($manga->title, 40, '...') }}
                            </div>
                            <div
                                class="position-absolute top-0 start-0 bg-{{ $manga->type === 'Manga' ? 'danger' : ($manga->type === 'Manhwa' ? 'success' : 'warning') }} text-white d-flex align-items-center p-1 rounded-br">
                                <small class="comic-type">{{ $manga->type }}</small>
                            </div>
                            <div
                                class="position-absolute top-50 start-0 bg-dark text-white d-flex align-items-center p-1 rounded-bl opacity-75">
                                <small class="comic-type">{{ $manga->status }}</small>
                            </div>
                        </div>
                    </a>
                </div>
                @if (($key + 1) % 6 === 0)
                    <hr class="col-12 my-4">
                @endif
            @endforeach
        </div>
        <div class="mt-1">
            {{ $latestUpdate->links() }}
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#genre').select2({
                theme: 'bootstrap-5',
                dropdownAutoWidth: true,
                width: '100%',
                maximumInputLength: 50,
            }).on('select2:open', function() {
                $('.select2-dropdown').css('max-height', '300px').css('overflow-y', 'auto');
            });
        });
    </script>
@endpush
