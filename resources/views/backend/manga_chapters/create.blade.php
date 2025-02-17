@extends('layouts.app')
@push('css')
    <style>
        .select2-container .select2-selection--single {
            background-color: #212529 !important;
            color: #ffffff !important;
            border: 1px solid #6c757d !important;
        }

        .select2-dropdown {
            background-color: #212529 !important;
            color: #ffffff !important;
            border: 1px solid #6c757d !important;
        }

        .select2-results__option {
            background-color: #212529 !important;
            color: #ffffff !important;
        }

        .select2-results__option--highlighted {
            background-color: #495057 !important;
            color: #ffffff !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #ffffff !important;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        @include('backend.partials.nav-admin')

        <div class="card shadow-sm mt-4">
            <div class="card-header">
                Add Manga Chapter
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('manga-chapters.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="manga_id" class="form-label">Select Manga</label>
                        <select name="manga_id" id="manga_id" class="form-control"></select>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Chapter Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="chapter_number" class="form-label">Chapter Number</label>
                        <input type="number" name="chapter_number" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="images" class="form-label">Upload Images</label>
                        <input type="file" name="images[]" class="form-control" multiple required>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Chapter</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#manga_id').select2({
                ajax: {
                    url: '{{ route('manga-chapters.manga-list') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                },
                minimumInputLength: 1
            });
        });
    </script>
@endpush
