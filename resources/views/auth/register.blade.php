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
        <p class="text-center">create new account</p>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" id="name" required autofocus autocomplete="name">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" required autofocus autocomplete="email">
            </div>
            <!-- role -->
            <div class="col-md-6 mb-3">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="">Select Role</option>
                    <option value="parent">parent</option>
                    <option value="driver">driver</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Password</label>
                <div class="position-relative">
                    <input type="password" name="password" id="passwordField"
                        class="form-control pe-5" required>

                    <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3"
                        id="togglePassword"
                        style="font-size: 1.2rem; cursor: pointer; color: #6c757d;"></i>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Confirm Password</label>
                <div class="position-relative">
                    <input type="password" name="password_confirmation" id="passwordConfirmationField"
                        class="form-control pe-5" required>

                    <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3"
                        id="toggleConfirmationPassword"
                        style="font-size: 1.2rem; cursor: pointer; color: #6c757d;"></i>
                </div>
            </div>

            <script>
                function togglePasswordVisibility(toggleId, fieldId) {
                    const toggleIcon = document.getElementById(toggleId);
                    const passwordField = document.getElementById(fieldId);

                    toggleIcon.addEventListener('click', function() {
                        const type = passwordField.type === 'password' ? 'text' : 'password';
                        passwordField.type = type;

                        this.classList.toggle('bi-eye');
                        this.classList.toggle('bi-eye-slash');
                    });
                }

                togglePasswordVisibility('togglePassword', 'passwordField');
                togglePasswordVisibility('toggleConfirmationPassword', 'passwordConfirmationField');
            </script>

            <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Create account</button>
            <div class="d-flex align-items-center justify-content-center">
                <p class="fs-4 mb-0 fw-bold">Already have account to eduride?</p>
                <a class="text-primary fw-bold ms-2" href="{{ route('login')}}">Log into account</a>
            </div>
        </form>
    </div>
</div>

@endsection