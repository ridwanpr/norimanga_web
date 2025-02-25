@extends('layouts.app')
@section('content')
    <section>
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card sticky-top bg-body-tertiary bg-opacity-75">
                        <div class="card-body">
                            <h4 class="card-title text-center">Register</h4>
                            <form action="{{ route('register.post') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp"
                                        name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 8 karakter" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required>
                                </div>
                                <x-turnstile />
                                <div class="d-grid gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary">Register</button>
                                </div>
                                <p class="text-center mt-3">Sudah punya akun? <a
                                        href="{{ route('login') }}">Login</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
