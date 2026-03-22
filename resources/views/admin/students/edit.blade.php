@extends('layouts.admin')
@section('page-title', 'Edit Student')
@section('breadcrumb', 'Students / Edit')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Edit Student</h1>
        <p>{{ $student->full_name }}</p>
    </div>
    <a href="{{ route('admin.students.show', $student) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.students.update', $student) }}">
@csrf @method('PUT')

{{-- Parent account --}}
<div class="card" style="margin-bottom:20px;">
    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-people-fill"></i> Parent Account</div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Parent Name <span class="req">*</span></label>
                <input type="text" name="parent_name" class="form-input {{ $errors->has('parent_name') ? 'error' : '' }}"
                       value="{{ old('parent_name', $student->user->name) }}" required>
                @error('parent_name') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Parent Email <span class="req">*</span></label>
                <input type="email" name="parent_email" class="form-input {{ $errors->has('parent_email') ? 'error' : '' }}"
                       value="{{ old('parent_email', $student->user->email) }}" required>
                @error('parent_email') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Parent Phone <span class="req">*</span></label>
                <input type="tel" name="parent_phone" class="form-input {{ $errors->has('parent_phone') ? 'error' : '' }}"
                       value="{{ old('parent_phone', $student->user->phone) }}" required>
                @error('parent_phone') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>

{{-- Student info --}}
<div class="card" style="margin-bottom:20px;">
    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-person-fill"></i> Student Information</div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Full Name <span class="req">*</span></label>
                <input type="text" name="full_name" class="form-input {{ $errors->has('full_name') ? 'error' : '' }}"
                       value="{{ old('full_name', $student->full_name) }}" required>
                @error('full_name') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">School ID</label>
                <input type="text" name="student_id" class="form-input"
                       value="{{ old('student_id', $student->student_id) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Grade</label>
                <input type="text" name="grade" class="form-input"
                       value="{{ old('grade', $student->grade) }}" placeholder="e.g. P5">
            </div>
            <div class="form-group">
                <label class="form-label">Class Section</label>
                <input type="text" name="class_section" class="form-input"
                       value="{{ old('class_section', $student->class_section) }}" placeholder="e.g. A">
            </div>
            <div class="form-group">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-input"
                       value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Active</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ old('is_active', $student->is_active) ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !old('is_active', $student->is_active) ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-section">
        <div class="form-section-title"><i class="bi bi-signpost-2-fill"></i> Transport Assignment</div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Route</label>
                <select name="route_id" class="form-select" id="routeSelect" onchange="loadStops(this.value)">
                    <option value="">— No route</option>
                    @foreach($routes as $route)
                        <option value="{{ $route->id }}" {{ old('route_id', $student->route_id) == $route->id ? 'selected' : '' }}>
                            {{ $route->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Boarding Stop</label>
                <select name="stop_id" class="form-select" id="stopSelect">
                    <option value="">— Select route first</option>
                    @foreach($stops as $stop)
                        <option value="{{ $stop->id }}" {{ old('stop_id', $student->stop_id) == $stop->id ? 'selected' : '' }}>
                            {{ $stop->order }}. {{ $stop->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group full">
                <label class="form-label">Medical Notes</label>
                <textarea name="medical_notes" class="form-textarea" placeholder="Allergies, conditions relevant to transport…">{{ old('medical_notes', $student->medical_notes) }}</textarea>
            </div>
        </div>
    </div>
</div>

<div style="display:flex;gap:12px;justify-content:flex-end;">
    <a href="{{ route('admin.students.show', $student) }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Changes</button>
</div>
</form>
@endsection

@push('scripts')
<script>
function loadStops(routeId) {
    var select = document.getElementById('stopSelect');
    select.innerHTML = '<option value="">Loading…</option>';
    if (!routeId) { select.innerHTML = '<option value="">— Select route first</option>'; return; }

    fetch('/admin/routes/' + routeId + '/stops-json')
        .then(function(r) { return r.json(); })
        .then(function(stops) {
            select.innerHTML = '<option value="">— Choose stop</option>';
            stops.forEach(function(s) {
                select.innerHTML += '<option value="' + s.id + '">' + s.order + '. ' + s.name + '</option>';
            });
        })
        .catch(function() { select.innerHTML = '<option value="">Error loading stops</option>'; });
}
</script>
@endpush