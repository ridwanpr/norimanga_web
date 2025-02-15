@extends('layouts.app')

@section('content')
    <div class="container">
        @include('backend.partials.nav-admin')

        <div class="col-12 mt-4">
            <div class="card shadow-sm">
                <div class="card-header">Manage Users</div>
                <div class="card-body p-0">
                    <div class="px-3 mt-3">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                    placeholder="Search User...">
                                <button type="submit" class="btn btn-secondary">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $count = ($users->currentPage() - 1) * $users->perPage() + 1; @endphp
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge">
                                                {{ $user->is_banned ? 'Banned' : 'Active' }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.users.ban', $user->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-{{ $user->is_banned ? 'danger' : 'success' }}">
                                                    {{ $user->is_banned ? 'Unban' : 'Ban' }}
                                                </button>
                                            </form>

                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#passwordModal{{ $user->id }}">
                                                Update Password
                                            </button>

                                            <div class="modal fade" id="passwordModal{{ $user->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Update Password for {{ $user->name }}
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form
                                                                action="{{ route('admin.users.update-password', $user->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="mb-3">
                                                                    <label for="password" class="form-label">New
                                                                        Password</label>
                                                                    <input type="password" name="password"
                                                                        class="form-control" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="password_confirmation"
                                                                        class="form-label">Confirm Password</label>
                                                                    <input type="password" name="password_confirmation"
                                                                        class="form-control" required>
                                                                </div>
                                                                <button type="submit"
                                                                    class="btn btn-primary">Update</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
