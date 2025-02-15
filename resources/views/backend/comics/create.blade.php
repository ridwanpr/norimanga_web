@extends('layouts.app')

@section('content')
    <div class="container">
        @include('backend.partials.nav-admin')

        <div class="card shadow-sm mt-4">
            <div class="card-header">Add Comic</div>
            <div class="card-body">
                <form action="{{ route('manage-comic.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">Select Status</option>
                            <option value="Ongoing">Ongoing</option>
                            <option value="Completed">Completed</option>
                            <option value="Dropped">Dropped</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="manga">Manga</option>
                            <option value="manhwa">Manhwa</option>
                            <option value="manhua">Manhua</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Release Year</label>
                        <select name="release_year" class="form-select">
                            @for ($year = date('Y'); $year >= 2010; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Author</label>
                        <input type="text" name="author" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Artist</label>
                        <input type="text" name="artist" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Synopsis</label>
                        <textarea name="synopsis" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cover</label>
                        <input type="file" name="cover" class="form-control">
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_project" class="form-check-input" value="1">
                        <label class="form-check-label">Is Project?</label>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_featured" class="form-check-input" value="1">
                        <label class="form-check-label">Is Featured?</label>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('manage-comic.index') }}" class="btn btn-secondary me-2">Back</a>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
