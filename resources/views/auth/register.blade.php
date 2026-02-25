@extends('layouts.guest')
@section('content')

<div class="card mb-0">
    <div class="card-body">
        <a href="/" class="text-nowrap logo-img text-center d-block py-3 w-100">
            EDURIDE
        </a>
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
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" autocomplete="current-password" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">password confirmation</label>
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" autocomplete="new-password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Create account</button>
            <div class="d-flex align-items-center justify-content-center">
                <p class="fs-4 mb-0 fw-bold">Already have account to eduride?</p>
                <a class="text-primary fw-bold ms-2" href="{{ route('login')}}">Log into account</a>
            </div>
        </form>
    </div>
</div>

@endsection