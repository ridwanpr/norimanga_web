@extends('layouts.app')
@push('css')
    @turnstileScripts()
@endpush
@section('title', 'Login - Baca Manga, Manhwa, Manhua Bahasa Indonesia - Panelesia')
@section('content')
    <section>
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card sticky-top bg-body-tertiary bg-opacity-75">
                        <div class="card-body">
                            <h4 class="card-title text-center">Login</h4>
                            <form method="POST" action="{{ route('login.post') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp"
                                        name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <x-turnstile />
                                <div class="d-grid gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </div>
                                <p class="text-center mt-3 mb-1">Belum punya akun? <a
                                        href="{{ route('register') }}">Register</a>
                                </p>
                                <p class="text-center mt-0">Hubungi admin jika lupa password</p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
