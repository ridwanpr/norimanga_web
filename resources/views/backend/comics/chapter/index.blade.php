@extends('layouts.app')

@section('content')
    <div class="container">
        @include('backend.partials.nav-admin')

        <div class="card shadow-sm mt-4">
            <div class="card-header">
                Manage Chapter | <strong>{{ $manga->title }}</strong>
                <a href="" class="btn btn-sm btn-primary float-end"><i class="bi bi-plus-circle"></i> Add Chapter</a>
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
                            @foreach ($chapters as $chapter)
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $chapters->links() }}
            </div>
        </div>
    </div>
@endsection
