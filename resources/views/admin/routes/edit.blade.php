@extends('layouts.admin')
@section('page-title', 'Edit Route')
@section('breadcrumb', 'Routes / Edit')

@section('content')

{{-- Flash messages --}}
@if(session('success'))
    <div class="flash flash-success" style="margin-bottom:20px;">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="flash flash-error" style="margin-bottom:20px;">
        <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
    </div>
@endif

{{-- Validation error summary --}}
@if($errors->any())
    <div class="flash flash-error" style="margin-bottom:20px;">
        <div>
            <div style="font-weight:700;margin-bottom:6px;">
                <i class="bi bi-exclamation-circle-fill"></i> Please fix the following:
            </div>
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
        <h1>Edit Route</h1>
        <p>{{ $route->name }}</p>
    </div>
    <a href="{{ route('admin.routes.show', $route) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.routes.update', $route) }}">
@csrf @method('PUT')

<div class="card" style="margin-bottom:20px;">
    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-signpost-2-fill"></i> Route Details</div>
        <div class="form-grid">

            <div class="form-group">
                <label class="form-label">Route Name <span class="req">*</span></label>
                <input type="text" name="name"
                       class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                       value="{{ old('name', $route->name) }}" required>
                @error('name') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Type <span class="req">*</span></label>
                <select name="type"
                        class="form-select {{ $errors->has('type') ? 'error' : '' }}"
                        required>
                    <option value="both"      {{ old('type', $route->type) === 'both'      ? 'selected' : '' }}>Morning &amp; Afternoon</option>
                    <option value="morning"   {{ old('type', $route->type) === 'morning'   ? 'selected' : '' }}>Morning only</option>
                    <option value="afternoon" {{ old('type', $route->type) === 'afternoon' ? 'selected' : '' }}>Afternoon only</option>
                </select>
                @error('type') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Morning Departure</label>
                <input type="time" name="morning_departure"
                       class="form-input {{ $errors->has('morning_departure') ? 'error' : '' }}"
                       value="{{ old('morning_departure', $route->morning_departure) }}">
                @error('morning_departure') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Afternoon Departure</label>
                <input type="time" name="afternoon_departure"
                       class="form-input {{ $errors->has('afternoon_departure') ? 'error' : '' }}"
                       value="{{ old('afternoon_departure', $route->afternoon_departure) }}">
                @error('afternoon_departure') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Est. Duration (minutes)</label>
                <input type="number" name="estimated_duration_min"
                       class="form-input {{ $errors->has('estimated_duration_min') ? 'error' : '' }}"
                       value="{{ old('estimated_duration_min', $route->estimated_duration_min) }}"
                       min="1">
                @error('estimated_duration_min') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Total Distance (km)</label>
                <input type="number" name="total_distance_km"
                       class="form-input {{ $errors->has('total_distance_km') ? 'error' : '' }}"
                       value="{{ old('total_distance_km', $route->total_distance_km) }}"
                       step="0.01" min="0">
                @error('total_distance_km') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group full">
                <label class="form-label">Description</label>
                <textarea name="description"
                          class="form-textarea {{ $errors->has('description') ? 'error' : '' }}">{{ old('description', $route->description) }}</textarea>
                @error('description') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Active</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ old('is_active', $route->is_active) ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !old('is_active', $route->is_active) ? 'selected' : '' }}>No</option>
                </select>
            </div>

        </div>
    </div>
</div>

<div style="display:flex;gap:12px;justify-content:flex-end;">
    <a href="{{ route('admin.routes.show', $route) }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg"></i> Save Changes
    </button>
</div>

</form>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.querySelector('.flash-error');
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        var first = document.querySelector('.form-input.error, .form-select.error, .form-textarea.error');
        if (first) first.focus();
    });
</script>
@endif

@endsection