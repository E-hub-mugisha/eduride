@extends('layouts.admin')
@section('page-title', 'Add User')
@section('breadcrumb', 'Users / Add')

@section('content')

@if($errors->any())
    <div class="flash flash-error" style="margin-bottom:20px;">
        <div>
            <div style="font-weight:700;margin-bottom:6px;"><i class="bi bi-exclamation-circle-fill"></i> Please fix the following:</div>
            <ul style="margin:0;padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li style="font-size:.84rem;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="page-header">
    <div class="page-header-left">
        <h1>Add New User</h1>
        <p>Create an admin, driver, or parent account.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.users.store') }}" id="userForm">
@csrf

<div class="card" style="margin-bottom:20px;">
    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-person-fill"></i> Account Details</div>
        <div class="form-grid">

            <div class="form-group">
                <label class="form-label">Full Name <span class="req">*</span></label>
                <input type="text" name="name" class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                       value="{{ old('name') }}" placeholder="Jean Pierre Nzeyimana" required>
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Role <span class="req">*</span></label>
                <select name="role" class="form-select {{ $errors->has('role') ? 'error' : '' }}"
                        id="roleSelect" onchange="toggleDriverFields()" required>
                    <option value="">— Select role</option>
                    <option value="admin"  {{ old('role') === 'admin'  ? 'selected' : '' }}>Admin</option>
                    <option value="driver" {{ old('role') === 'driver' ? 'selected' : '' }}>Driver</option>
                    <option value="parent" {{ old('role') === 'parent' ? 'selected' : '' }}>Parent</option>
                </select>
                @error('role') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email Address <span class="req">*</span></label>
                <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                       value="{{ old('email') }}" placeholder="user@irerero.rw" required>
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-input {{ $errors->has('phone') ? 'error' : '' }}"
                       value="{{ old('phone') }}" placeholder="+250 7XX XXX XXX">
                @error('phone') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password <span class="req">*</span></label>
                <input type="password" name="password" class="form-input" placeholder="Min. 8 characters" required>
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password <span class="req">*</span></label>
                <input type="password" name="password_confirmation" class="form-input" placeholder="Repeat password" required>
            </div>

        </div>
    </div>

    {{-- Driver-only section --}}
    <div class="form-section" id="driverSection" style="display:none;">
        <div class="form-section-title"><i class="bi bi-card-text"></i> Driver Details</div>
        <div class="form-grid">

            <div class="form-group">
                <label class="form-label">License Number <span class="req">*</span></label>
                <input type="text" name="license_number" class="form-input {{ $errors->has('license_number') ? 'error' : '' }}"
                       value="{{ old('license_number') }}" placeholder="RW-DL-20210001">
                @error('license_number') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">License Expiry</label>
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
                <p class="form-hint">Only unassigned active vehicles shown.</p>
            </div>

        </div>
    </div>
</div>

<div style="display:flex;gap:12px;justify-content:flex-end;">
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg"></i> Create User
    </button>
</div>

</form>

@push('scripts')
<script>
function toggleDriverFields() {
    var role    = document.getElementById('roleSelect').value;
    var section = document.getElementById('driverSection');
    section.style.display = role === 'driver' ? 'block' : 'none';
}
// Run on load in case old() restored the value
document.addEventListener('DOMContentLoaded', toggleDriverFields);
</script>
@endpush

@endsection