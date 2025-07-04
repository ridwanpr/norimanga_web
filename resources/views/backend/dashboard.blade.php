@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="container">
            <div class="row g-2">
                <div class="col-12 col-md-3">
                    <div class="card bg-primary text-white shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Users</h6>
                                    <h2 class="mt-2 mb-0">{{ $totalUser }}</h2>
                                </div>
                                <i class="bi bi-people fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card bg-success text-white shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Comics</h6>
                                    <h2 class="mt-2 mb-0">{{ $totalComic }}</h2>
                                </div>
                                <i class="bi bi-book fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card bg-info text-white shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Chapters</h6>
                                    <h2 class="mt-2 mb-0">{{ $totalChapter }}</h2>
                                </div>
                                <i class="bi bi-file-earmark-text fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card bg-warning text-white shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Projects</h6>
                                    <h2 class="mt-2 mb-0">{{ $totalProject }}</h2>
                                </div>
                                <i class="bi bi-folder fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('backend.partials.nav-admin')
        </div>

        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm mt-2">
                    <div class="card-header bg-primary text-white">Latest Registered Users</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Registered At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latestUsers as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at->format('d M Y H:i:s') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card shadow-sm mt-2">
                    <div class="card-header bg-success text-white">Latest Created Comic</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latestManga as $manga)
                                        <tr>
                                            <td>{{ $manga->title }}</td>
                                            <td>{{ $manga->created_at->format('d M Y H:i:s') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card shadow-sm mt-2">
                    <div class="card-header bg-info text-white">Latest Updated Chapters</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Chapter</th>
                                        <th>Updated At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latestChapters as $chapter)
                                        <tr>
                                            <td>{{ $chapter->manga->title }}</td>
                                            <td>{{ $chapter->title }}</td>
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
@endsection
