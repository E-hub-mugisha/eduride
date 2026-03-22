@extends('layouts.admin')
@section('page-title', 'Edit Vehicle')
@section('breadcrumb', 'Vehicles / Edit')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Edit Vehicle</h1>
        <p>{{ $vehicle->plate_number }} · {{ $vehicle->brand }} {{ $vehicle->model }}</p>
    </div>
    <a href="{{ route('admin.vehicles.show', $vehicle) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.vehicles.update', $vehicle) }}" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="card" style="margin-bottom:20px;">
    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-truck-front-fill"></i> Vehicle Details</div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Plate Number <span class="req">*</span></label>
                <input type="text" name="plate_number" class="form-input {{ $errors->has('plate_number') ? 'error' : '' }}"
                       value="{{ old('plate_number', $vehicle->plate_number) }}" required>
                @error('plate_number') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status <span class="req">*</span></label>
                <select name="status" class="form-select" required>
                    @foreach(['active' => 'Active', 'maintenance' => 'Maintenance', 'inactive' => 'Inactive'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $vehicle->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Brand</label>
                <input type="text" name="brand" class="form-input"
                       value="{{ old('brand', $vehicle->brand) }}" placeholder="e.g. Toyota">
            </div>

            <div class="form-group">
                <label class="form-label">Model <span class="req">*</span></label>
                <input type="text" name="model" class="form-input {{ $errors->has('model') ? 'error' : '' }}"
                       value="{{ old('model', $vehicle->model) }}" required>
                @error('model') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Color</label>
                <input type="text" name="color" class="form-input"
                       value="{{ old('color', $vehicle->color) }}">
            </div>

            <div class="form-group">
                <label class="form-label">Seating Capacity <span class="req">*</span></label>
                <input type="number" name="capacity" class="form-input {{ $errors->has('capacity') ? 'error' : '' }}"
                       value="{{ old('capacity', $vehicle->capacity) }}" min="1" max="100" required>
                @error('capacity') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Year Manufactured</label>
                <input type="number" name="year_manufactured" class="form-input"
                       value="{{ old('year_manufactured', $vehicle->year_manufactured) }}"
                       min="1990" max="{{ date('Y') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Photo</label>
                @if($vehicle->photo)
                    <div style="margin-bottom:8px;">
                        <img src="{{ $vehicle->photo_url }}" style="height:60px;border-radius:8px;border:1px solid rgba(255,255,255,.1);" alt="">
                    </div>
                @endif
                <input type="file" name="photo" class="form-input" accept="image/*">
                <p class="form-hint">Leave empty to keep current photo.</p>
            </div>

            <div class="form-group full">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-textarea">{{ old('notes', $vehicle->notes) }}</textarea>
            </div>
        </div>
    </div>
</div>

<div style="display:flex;gap:12px;justify-content:flex-end;">
    <a href="{{ route('admin.vehicles.show', $vehicle) }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg"></i> Save Changes
    </button>
</div>

</form>
@endsection