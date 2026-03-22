@extends('layouts.admin')
@section('page-title', 'New Route')
@section('breadcrumb', 'Routes / New')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Create Route</h1>
        <p>Define a new transport route. Add stops after saving.</p>
    </div>
    <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.routes.store') }}">
@csrf
<div class="card" style="margin-bottom:20px;">
    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-signpost-2-fill"></i> Route Details</div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Route Name <span class="req">*</span></label>
                <input type="text" name="name" class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                       value="{{ old('name') }}" placeholder="e.g. Route A – Kicukiro" required>
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Type <span class="req">*</span></label>
                <select name="type" class="form-select" required>
                    <option value="both"      {{ old('type','both') === 'both'      ? 'selected' : '' }}>Morning & Afternoon</option>
                    <option value="morning"   {{ old('type') === 'morning'          ? 'selected' : '' }}>Morning only</option>
                    <option value="afternoon" {{ old('type') === 'afternoon'        ? 'selected' : '' }}>Afternoon only</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Morning Departure</label>
                <input type="time" name="morning_departure" class="form-input"
                       value="{{ old('morning_departure', '06:30') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Afternoon Departure</label>
                <input type="time" name="afternoon_departure" class="form-input"
                       value="{{ old('afternoon_departure', '16:30') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Est. Duration (minutes)</label>
                <input type="number" name="estimated_duration_min" class="form-input"
                       value="{{ old('estimated_duration_min') }}" min="1" placeholder="e.g. 45">
            </div>

            <div class="form-group">
                <label class="form-label">Total Distance (km)</label>
                <input type="number" name="total_distance_km" class="form-input" step="0.01"
                       value="{{ old('total_distance_km') }}" min="0" placeholder="e.g. 12.5">
            </div>

            <div class="form-group full">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-textarea" placeholder="Brief description of areas covered…">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Active</label>
                <select name="is_active" class="form-select">
                    <option value="1" selected>Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div style="display:flex;gap:12px;justify-content:flex-end;">
    <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Create Route</button>
</div>
</form>
@endsection