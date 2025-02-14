@extends('layouts.app')
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @vite('resources/css/select2.css')
@endpush
@section('content')
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between">
            <h1 class="fs-5 mb-3 fw-bold text-white">Daftar Komik</h1>
            <a href="">Text Mode</a>
        </div>
        <div class="filtering mb-4">
            <form class="row row-cols-2 row-cols-md-auto g-2 align-items-center">
                <div class="col">
                    <select name="genre" id="genre" class="form-select">
                        <option value="">Genre</option>
                        <option value="1">Action</option>
                        <option value="2">Adventure</option>
                        <option value="3">Comedy</option>
                    </select>
                </div>
                <div class="col">
                    <select name="year" id="year" class="form-select">
                        <option value="">Tahun</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                        <option value="2020">2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                    </select>
                </div>
                <div class="col">
                    <select name="type" id="type" class="form-select">
                        <option value="">Tipe</option>
                        <option value="manga">Manga</option>
                        <option value="manhwa">Manhwa</option>
                        <option value="manhua">Manhua</option>
                    </select>
                </div>
                <div class="col">
                    <select name="status" id="status" class="form-select">
                        <option value="">Status</option>
                        <option value="completed">Completed</option>
                        <option value="ongoing">Ongoing</option>
                    </select>
                </div>
                <div class="col-12 col-md-auto">
                    <button type="submit" class="btn btn-grey w-100 w-md-auto">Filter</button>
                </div>
            </form>
        </div>
        <div class="row g-2">
            @for ($i = 0; $i < 18; $i++)
                <div class="col-6 col-md-2">
                    <a href="" class="text-decoration-none">
                        <div class="image-container mb-1">
                            <img src="https://placehold.co/250x300" class="img-fluid rounded fixed-size-latest"
                                alt="">
                            <div class="image-title">
                                Title Here
                            </div>
                        </div>
                    </a>
                </div>
            @endfor
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
                theme: 'bootstrap-5'
            });
            $('#year').select2({
                theme: 'bootstrap-5'
            });
            $('#type').select2({
                theme: 'bootstrap-5'
            });
            $('#status').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>
@endpush
