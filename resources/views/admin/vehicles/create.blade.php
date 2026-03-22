@extends('layouts.admin')
@section('page-title', 'Add Vehicle')
@section('breadcrumb', 'Vehicles / Add')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Add New Vehicle</h1>
        <p>Register a new bus or transport vehicle to the fleet.</p>
    </div>
    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.vehicles.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="card" style="margin-bottom:20px;">
        <div class="form-section">
            <div class="form-section-title"><i class="bi bi-truck-front-fill"></i> Vehicle Details</div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Plate Number <span class="req">*</span></label>
                    <input type="text" name="plate_number" class="form-input {{ $errors->has('plate_number') ? 'error' : '' }}"
                        value="{{ old('plate_number') }}" placeholder="e.g. RAB 001 A" required>
                    @error('plate_number') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Status <span class="req">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(['active' => 'Active', 'maintenance' => 'Maintenance', 'inactive' => 'Inactive'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', 'active') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Brand <span class="req">*</span></label>
                    <input type="text" name="brand" class="form-input {{ $errors->has('brand') ? 'error' : '' }}"
                        value="{{ old('brand') }}" placeholder="e.g. Toyota" required>
                    @error('brand') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Model <span class="req">*</span></label>
                    <input type="text" name="model" class="form-input {{ $errors->has('model') ? 'error' : '' }}"
                        value="{{ old('model') }}" placeholder="e.g. Coaster" required>
                    @error('model') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Color</label>
                    <input type="text" name="color" class="form-input"
                        value="{{ old('color') }}" placeholder="e.g. Yellow">
                </div>

                <div class="form-group">
                    <label class="form-label">Seating Capacity <span class="req">*</span></label>
                    <input type="number" name="capacity" class="form-input {{ $errors->has('capacity') ? 'error' : '' }}"
                        value="{{ old('capacity', 30) }}" min="1" max="100" required>
                    @error('capacity') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Year Manufactured</label>
                    <input type="number" name="year_manufactured" class="form-input"
                        value="{{ old('year_manufactured') }}" min="1990" max="{{ date('Y') }}"
                        placeholder="{{ date('Y') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Photo</label>
                    <input type="file" name="photo" class="form-input" accept="image/*">
                    <p class="form-hint">Max 2MB. JPG or PNG.</p>
                </div>

                <div class="form-group full">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-textarea" placeholder="Any additional notes…">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:12px;justify-content:flex-end;">
        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Save Vehicle
        </button>
    </div>

</form>
@endsection