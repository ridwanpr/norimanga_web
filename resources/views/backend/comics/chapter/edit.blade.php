@extends('layouts.app')

@section('content')
    <div class="container">
        @include('backend.partials.nav-admin')

        <div class="card shadow-sm mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Chapter | <span class="fw-bold">{{ $chapter->manga->title }}</span></h5>
                <a href="{{ route('manga-chapters.index') }}" class="btn btn-sm btn-outline-secondary">Back to Chapter
                    List</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('manga-chapters.update', $chapter->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="title" class="form-label">Chapter Title</label>
                        <input type="text" name="title" class="form-control"
                            value="{{ old('title', $chapter->title) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="chapter_number" class="form-label">Chapter Number</label>
                        <input type="text" name="chapter_number" class="form-control"
                            value="{{ old('chapter_number', $chapter->chapter_number) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bucket Image</label>
                        <select name="bucket" class="form-select" required>
                            <option value="">Select Bucket</option>
                            <option value="s1">s1</option>
                            <option value="s2">s2</option>
                            <option value="s3">s3</option>
                            <option value="s4">s4</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="images" class="form-label">Upload Images</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                        <small class="form-text text-muted">You can upload multiple images.</small>
                    </div>

                    <div class="mb-3 text-center">
                        <label for="images" class="form-label">Preview Chapter</label>
                        <div id="preview-container" style="max-height: 400px; overflow-y: auto;">
                            @foreach ($formattedImages as $img)
                                <img src="{{ $img }}" class="img-fluid mb-2" alt="{{ $chapter->title }}"
                                    style="max-width: 400px; display: block; margin: 0 auto;">
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save Chapter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const imageInput = document.querySelector('input[name="images[]"]');
            const previewContainer = document.querySelector("#preview-container");

            imageInput.addEventListener("change", function() {
                previewContainer.innerHTML = "";

                Array.from(this.files).forEach(file => {
                    if (file.type.startsWith("image/")) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement("img");
                            img.src = e.target.result;
                            img.classList.add("img-fluid", "mb-2");
                            img.style.maxWidth = "400px";
                            img.style.display = "block";
                            img.style.margin = "0 auto";
                            previewContainer.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });
        });
    </script>
@endpush
