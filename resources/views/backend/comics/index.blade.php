@extends('layouts.app')

@section('content')
    <div class="container">
        @include('backend.partials.nav-admin')

        <div class="card shadow-sm mt-4">
            <div class="card-header">
                Manage Comics
                <a href="{{ route('manage-comic.create') }}" class="btn btn-sm btn-primary float-end"><i
                        class="bi bi-plus-circle"></i> Add Comic</a>
            </div>
            <div class="card-body p-0">
                <div class="px-3 mt-3">
                    <form method="GET" action="{{ route('manage-comic.index') }}" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Search Comics...">
                            <button type="submit" class="btn btn-secondary">Search</button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Featured</th>
                                <th>Project</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comics as $index => $comic)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ Str::limit($comic->title, 40) }}</td>
                                    <td>{{ Str::limit($comic->slug, 40) }}</td>
                                    <td>{{ $comic->detail->status ?? 'N/A' }}</td>
                                    <td>{{ $comic->is_featured ? 'Yes' : 'No' }}</td>
                                    <td>{{ $comic->is_project ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <a href="{{ route('manage-comic.edit', $comic->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('manage-comic.destroy', $comic->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $comics->links() }}
            </div>
        </div>
    </div>
@endsection
