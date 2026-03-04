@extends('layouts.guest')
@section('content')
<!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<div class="card mb-0">
    <div class="card-body">
        <a href="/" class="text-nowrap logo-img text-center d-block py-3 w-100">
            EDURIDE
        </a>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <p class="text-center">Log into your account</p>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Username/Email</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="position-relative">
                    <input type="password" name="password" id="passwordField"
                        class="form-control pe-5" required>

                    <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer"
                        id="togglePassword"
                        style="font-size: 1.2rem; cursor: pointer; color: #6c757d;"></i>
                </div>
            </div>

            <script>
                const togglePassword = document.getElementById('togglePassword');
                const passwordField = document.getElementById('passwordField');

                togglePassword.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);

                    this.classList.toggle('bi-eye');
                    this.classList.toggle('bi-eye-slash');
                });
            </script>

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