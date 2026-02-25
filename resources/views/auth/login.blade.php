@extends('layouts.guest')
@section('content')

<div class="card mb-0">
    <div class="card-body">
        <a href="/" class="text-nowrap logo-img text-center d-block py-3 w-100">
            EDURIDE
        </a>
        <p class="text-center">Log into your account</p>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Username/Email</label>
                <input type="email" class="form-control" name="email" id="email" required autofocus autocomplete="username">
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" autocomplete="current-password" required>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input primary" type="checkbox" value="" id="remember_me" name="remember">
                    <label class="form-check-label text-dark" for="flexCheckChecked">
                        Remember this Device
                    </label>
                </div>
                <a class="text-primary fw-bold" href="{{ route('password.request') }}">Forgot Password ?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Sign In</button>
            <div class="d-flex align-items-center justify-content-center">
                <p class="fs-4 mb-0 fw-bold">New to eduride?</p>
                <a class="text-primary fw-bold ms-2" href="{{ route('register')}}">Create an account</a>
            </div>
        </form>
    </div>
</div>

@endsection