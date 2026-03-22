@extends('layouts.admin')
@section('page-title', 'Edit Driver')
@section('breadcrumb', 'Drivers / Edit')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Edit Driver</h1>
        <p>{{ $driver->name }}</p>
    </div>
    <a href="{{ route('admin.drivers.show', $driver) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.drivers.update', $driver) }}">
@csrf @method('PUT')

<div class="card" style="margin-bottom:20px;">
    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-person-fill"></i> Personal Information</div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Full Name <span class="req">*</span></label>
                <input type="text" name="name" class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                       value="{{ old('name', $driver->user->name) }}" required>
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Phone <span class="req">*</span></label>
                <input type="tel" name="phone" class="form-input {{ $errors->has('phone') ? 'error' : '' }}"
                       value="{{ old('phone', $driver->user->phone) }}" required>
                @error('phone') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email <span class="req">*</span></label>
                <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                       value="{{ old('email', $driver->user->email) }}" required>
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-input" placeholder="Leave blank to keep current">
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-input" placeholder="Repeat new password">
            </div>
        </div>
    </div>

    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-card-text"></i> License & Assignment</div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">License Number <span class="req">*</span></label>
                <input type="text" name="license_number" class="form-input {{ $errors->has('license_number') ? 'error' : '' }}"
                       value="{{ old('license_number', $driver->license_number) }}" required>
                @error('license_number') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">License Expiry</label>
                <input type="date" name="license_expiry" class="form-input"
                       value="{{ old('license_expiry', $driver->license_expiry?->format('Y-m-d')) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Assigned Vehicle</label>
                <select name="vehicle_id" class="form-select">
                    <option value="">— No vehicle</option>
                    @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" {{ old('vehicle_id', $driver->vehicle_id) == $v->id ? 'selected' : '' }}>
                            {{ $v->plate_number }} · {{ $v->brand }} {{ $v->model }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Status <span class="req">*</span></label>
                <select name="status" class="form-select" required>
                    @foreach(['available' => 'Available', 'on_trip' => 'On Trip', 'off_duty' => 'Off Duty', 'suspended' => 'Suspended'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $driver->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group full">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-textarea">{{ old('notes', $driver->notes) }}</textarea>
            </div>
        </div>
    </div>
</div>

<div style="display:flex;gap:12px;justify-content:flex-end;">
    <a href="{{ route('admin.drivers.show', $driver) }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Changes</button>
</div>
</form>
@endsection