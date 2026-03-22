@extends('layouts.admin')
@section('page-title', 'Add Driver')
@section('breadcrumb', 'Drivers / Add')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Register New Driver</h1>
        <p>Creates a driver profile and a linked login account.</p>
    </div>
    <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.drivers.store') }}">
@csrf

{{-- Personal info --}}
<div class="card" style="margin-bottom:20px;">
    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-person-fill"></i> Personal Information</div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Full Name <span class="req">*</span></label>
                <input type="text" name="name" class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                       value="{{ old('name') }}" placeholder="Jean Pierre Nzeyimana" required>
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number <span class="req">*</span></label>
                <input type="tel" name="phone" class="form-input {{ $errors->has('phone') ? 'error' : '' }}"
                       value="{{ old('phone') }}" placeholder="+250 7XX XXX XXX" required>
                @error('phone') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email Address <span class="req">*</span></label>
                <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                       value="{{ old('email') }}" placeholder="driver@irerero.rw" required>
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password <span class="req">*</span></label>
                <input type="password" name="password" class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                       placeholder="Min. 8 characters" required>
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password <span class="req">*</span></label>
                <input type="password" name="password_confirmation" class="form-input" placeholder="Repeat password" required>
            </div>
        </div>
    </div>

    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-card-text"></i> License & Assignment</div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">License Number <span class="req">*</span></label>
                <input type="text" name="license_number" class="form-input {{ $errors->has('license_number') ? 'error' : '' }}"
                       value="{{ old('license_number') }}" placeholder="RW-DL-20210001" required>
                @error('license_number') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">License Expiry Date</label>
                <input type="date" name="license_expiry" class="form-input"
                       value="{{ old('license_expiry') }}" min="{{ date('Y-m-d') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Assign Vehicle</label>
                <select name="vehicle_id" class="form-select">
                    <option value="">— No vehicle yet</option>
                    @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" {{ old('vehicle_id') == $v->id ? 'selected' : '' }}>
                            {{ $v->plate_number }} · {{ $v->brand }} {{ $v->model }}
                        </option>
                    @endforeach
                </select>
                <p class="form-hint">Only unassigned active vehicles are shown.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Initial Status <span class="req">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="available" selected>Available</option>
                    <option value="off_duty">Off Duty</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>

            <div class="form-group full">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-textarea" placeholder="Any additional notes…">{{ old('notes') }}</textarea>
            </div>
        </div>
    </div>
</div>

<div style="display:flex;gap:12px;justify-content:flex-end;">
    <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg"></i> Register Driver
    </button>
</div>

</form>
@endsection