@extends('layouts.admin')
@section('page-title', 'Edit User')
@section('breadcrumb', 'Users / Edit')

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
        <h1>Edit User</h1>
        <p>{{ $user->name }} · {{ ucfirst($user->role) }}</p>
    </div>
    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.users.update', $user) }}" id="userForm">
@csrf @method('PUT')

<div class="card" style="margin-bottom:20px;">
    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-person-fill"></i> Account Details</div>
        <div class="form-grid">

            <div class="form-group">
                <label class="form-label">Full Name <span class="req">*</span></label>
                <input type="text" name="name" class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                       value="{{ old('name', $user->name) }}" required>
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Role <span class="req">*</span></label>
                <select name="role" class="form-select" id="roleSelect" onchange="toggleDriverFields()" required>
                    <option value="admin"  {{ old('role', $user->role) === 'admin'  ? 'selected' : '' }}>Admin</option>
                    <option value="driver" {{ old('role', $user->role) === 'driver' ? 'selected' : '' }}>Driver</option>
                    <option value="parent" {{ old('role', $user->role) === 'parent' ? 'selected' : '' }}>Parent</option>
                </select>
                @error('role') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email Address <span class="req">*</span></label>
                <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                       value="{{ old('email', $user->email) }}" required>
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-input {{ $errors->has('phone') ? 'error' : '' }}"
                       value="{{ old('phone', $user->phone) }}" placeholder="+250 7XX XXX XXX">
                @error('phone') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-input" placeholder="Leave blank to keep current">
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-input" placeholder="Repeat new password">
            </div>

        </div>
    </div>

    {{-- Driver-only fields --}}
    <div class="form-section" id="driverSection"
         style="display:{{ old('role', $user->role) === 'driver' ? 'block' : 'none' }};">
        <div class="form-section-title"><i class="bi bi-card-text"></i> Driver Details</div>
        <div class="form-grid">

            <div class="form-group">
                <label class="form-label">License Number</label>
                <input type="text" name="license_number" class="form-input {{ $errors->has('license_number') ? 'error' : '' }}"
                       value="{{ old('license_number', $user->driver?->license_number) }}"
                       placeholder="RW-DL-20210001">
                @error('license_number') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">License Expiry</label>
                <input type="date" name="license_expiry" class="form-input"
                       value="{{ old('license_expiry', $user->driver?->license_expiry?->format('Y-m-d')) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Assigned Vehicle</label>
                <select name="vehicle_id" class="form-select">
                    <option value="">— No vehicle</option>
                    @foreach($vehicles as $v)
                        <option value="{{ $v->id }}"
                                {{ old('vehicle_id', $user->driver?->vehicle_id) == $v->id ? 'selected' : '' }}>
                            {{ $v->plate_number }} · {{ $v->brand }} {{ $v->model }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Driver Status</label>
                <select name="status" class="form-select">
                    @foreach(['available'=>'Available','on_trip'=>'On Trip','off_duty'=>'Off Duty','suspended'=>'Suspended'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $user->driver?->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>
</div>

<div style="display:flex;gap:12px;justify-content:flex-end;">
    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg"></i> Save Changes
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
document.addEventListener('DOMContentLoaded', toggleDriverFields);
</script>
@endpush

@endsection