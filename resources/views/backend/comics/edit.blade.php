@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit Comic: {{ $manage_comic->title }}</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('manage-comic.update', $manage_comic->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $manage_comic->title) }}"
                    required>
            </div>

            <!-- Slug -->
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $manage_comic->slug) }}"
                    required>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="Ongoing"
                        {{ isset($manage_comic->detail) && $manage_comic->detail->status == 'Ongoing' ? 'selected' : '' }}>
                        Ongoing</option>
                    <option value="Completed"
                        {{ isset($manage_comic->detail) && $manage_comic->detail->status == 'Completed' ? 'selected' : '' }}>
                        Completed</option>
                    <option value="Dropped"
                        {{ isset($manage_comic->detail) && $manage_comic->detail->status == 'Dropped' ? 'selected' : '' }}>
                        Dropped</option>
                </select>
            </div>

            <!-- Type -->
            <div class="mb-3">
                <label class="form-label">Type</label>
                <input type="text" name="type" class="form-control"
                    value="{{ isset($manage_comic->detail) ? old('type', $manage_comic->detail->type) : '' }}" required>
            </div>

            <!-- Release Year -->
            <div class="mb-3">
                <label class="form-label">Release Year</label>
                <input type="number" name="release_year" class="form-control"
                    value="{{ old('release_year', isset($manage_comic->detail) ? $manage_comic->detail->release_year : '') }}">
            </div>

            <!-- Author -->
            <div class="mb-3">
                <label class="form-label">Author</label>
                <input type="text" name="author" class="form-control"
                    value="{{ old('author', isset($manage_comic->detail) ? $manage_comic->detail->author : '') }}">
            </div>

            <!-- Artist -->
            <div class="mb-3">
                <label class="form-label">Artist</label>
                <input type="text" name="artist" class="form-control"
                    value="{{ old('artist', isset($manage_comic->detail) ? $manage_comic->detail->artist : '') }}">
            </div>

            <!-- Synopsis -->
            <div class="mb-3">
                <label class="form-label">Synopsis</label>
                <textarea name="synopsis" class="form-control" rows="4">{{ old('synopsis', isset($manage_comic->detail) ? $manage_comic->detail->synopsis : '') }}</textarea>
            </div>

            <!-- Cover Image -->
            <div class="mb-3">
                <label class="form-label">Cover Image</label>
                <input type="file" name="cover" class="form-control">

                @if (isset($manage_comic->detail->cover))
                    <div class="mt-2">
                        <img src="{{ str_replace('.s3.tebi.io', '', $manage_comic->detail->cover) }}" alt="Cover Image"
                            class="img-thumbnail" width="150">
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Update Comic</button>
            <a href="{{ route('manage-comic.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection

