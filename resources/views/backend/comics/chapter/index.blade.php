@extends('layouts.app')

@section('content')
    <div class="container">
        @include('backend.partials.nav-admin')

        <div class="card shadow-sm mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    Manage Chapter | <strong>{{ $manga->title }}</strong>
                </div>
                <form method="GET" action="{{ route('chapter.index', $manga->id) }}" class="d-flex">
                    <input type="text" name="search" class="form-control form-control-sm me-2"
                        placeholder="Search chapter..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-secondary"><i class="bi bi-search"></i></button>
                </form>
                <a href="{{ route('chapter.create', $manga->id) }}" class="btn btn-sm btn-primary"><i
                        class="bi bi-plus-circle"></i> Add Chapter</a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Chapter Title</th>
                                <th>Chapter Number</th>
                                <th>Slug</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($chapters as $chapter)
                                <tr>
                                    <td>{{ ($chapters->currentPage() - 1) * $chapters->perPage() + $loop->iteration }}</td>
                                    <td>{{ Str::limit($chapter->title, 40) }}</td>
                                    <td>{{ $chapter->chapter_number }}</td>
                                    <td>{{ $chapter->slug }}</td>
                                    <td>
                                        <a href="{{ route('chapter.edit', [$chapter->manga_id, $chapter->id]) }}"
                                            class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('manga-chapters.destroy', $chapter->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No chapters found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $chapters->appends(['search' => request('search')])->links() }}
            </div>
        </div>
    </div>
@endsection
