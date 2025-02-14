@extends('layouts.app')
@push('css')
    <style>
        .alert:hover {
            cursor: pointer;
            opacity: 0.75;
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="row mb-2">
            <div class="col-12">
                <h2 class="fs-4 fw-bold"><span class="text-primary">My </span>Account </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-0">Hello {{ ucwords($userData->name) }}</h5>
                                <p class="card-text">{{ $userData->email }}</p>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger text-decoration-none"><i
                                            class="bi bi-box-arrow-right"></i>&nbsp;Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="" class="text-decoration-none">
                    <div class="alert alert-info mt-3 w-100" role="alert">
                        <i class="bi bi-bookmark-fill text-info"></i> Klik disini untuk pergi ke
                        <strong>Bookmark</strong>.
                    </div>
                </a>
                <a href="" class="text-decoration-none">
                    <div class="alert alert-success mt-3 w-100" role="alert">
                        <i class="bi bi-bar-chart-fill text-success"></i> Klik disini untuk lihat <strong>Statistik
                            Akun</strong> anda.
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-9">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Profile</h5>
                        <form method="POST" action="{{ route('update-profile', $userData->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $userData->name }}" required>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ $userData->email }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="old_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="old_password" name="old_password"
                                        required>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                    <div id="passwordHelpBlock" class="form-text">
                                        Kosongkan jika tidak ingin mengubah password
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm float-end">
                                <i class="bi bi-save"></i> Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
