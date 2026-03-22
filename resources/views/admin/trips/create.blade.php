@extends('layouts.admin')
@section('page-title', 'Schedule Trip')
@section('breadcrumb', 'Trips / Schedule')

@section('content')

{{-- Validation errors --}}
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
        <h1>Schedule New Trip</h1>
        <p>Assign a route, driver, and vehicle to a scheduled departure.</p>
    </div>
    <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<form method="POST" action="{{ route('admin.trips.store') }}">
    @csrf

    <div class="card" style="margin-bottom:20px;">
        <div class="form-section">
            <div class="form-section-title"><i class="bi bi-signpost-2-fill"></i> Route</div>
            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">Route <span class="req">*</span></label>
                    <select name="route_id"
                        class="form-select {{ $errors->has('route_id') ? 'error' : '' }}"
                        id="routeSelect"
                        onchange="updateScheduleHint()"
                        required>
                        <option value="">— Select a route</option>
                        @foreach($routes as $route)
                        <option value="{{ $route->id }}"
                            data-morning="{{ $route->morning_departure }}"
                            data-afternoon="{{ $route->afternoon_departure }}"
                            {{ old('route_id') == $route->id ? 'selected' : '' }}>
                            {{ $route->name }} — {{ $route->type_label }}
                        </option>
                        @endforeach
                    </select>
                    @error('route_id') <p class="form-error">{{ $message }}</p> @enderror
                    <p class="form-hint" id="scheduleHint" style="display:none;color:var(--teal);">
                        <i class="bi bi-clock"></i> <span id="scheduleHintText"></span>
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">Trip Type <span class="req">*</span></label>
                    <select name="type"
                        class="form-select {{ $errors->has('type') ? 'error' : '' }}"
                        required>
                        <option value="morning" {{ old('type') === 'morning'   ? 'selected' : '' }}>Morning</option>
                        <option value="afternoon" {{ old('type') === 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                        <option value="special" {{ old('type') === 'special'   ? 'selected' : '' }}>Special / One-off</option>
                    </select>
                    @error('type') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Scheduled Date &amp; Time <span class="req">*</span></label>
                    <input type="datetime-local" name="scheduled_at"
                        class="form-input {{ $errors->has('scheduled_at') ? 'error' : '' }}"
                        value="{{ old('scheduled_at', now()->format('Y-m-d\TH:i')) }}"
                        min="{{ now()->format('Y-m-d\TH:i') }}"
                        required>
                    @error('scheduled_at') <p class="form-error">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title"><i class="bi bi-person-badge-fill"></i> Driver &amp; Vehicle</div>
            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">Driver <span class="req">*</span></label>
                    <select name="driver_id"
                        class="form-select {{ $errors->has('driver_id') ? 'error' : '' }}"
                        id="driverSelect"
                        onchange="syncVehicle()"
                        required>
                        <option value="">— Select a driver</option>
                        @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}"
                            data-vehicle="{{ $driver->vehicle_id }}"
                            data-status="{{ $driver->status }}"
                            {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                            {{ $driver->name }}
                            @if($driver->vehicle) · {{ $driver->vehicle->plate_number }} @endif
                            ({{ ucfirst(str_replace('_', ' ', $driver->status)) }})
                        </option>
                        @endforeach
                    </select>
                    @error('driver_id') <p class="form-error">{{ $message }}</p> @enderror
                    <p class="form-hint">Only available and off-duty drivers are shown.</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Vehicle <span class="req">*</span></label>
                    <select name="vehicle_id"
                        class="form-select {{ $errors->has('vehicle_id') ? 'error' : '' }}"
                        id="vehicleSelect"
                        required>
                        <option value="">— Select a vehicle</option>
                        @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}"
                            {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                            {{ $vehicle->plate_number }} — {{ $vehicle->brand }} {{ $vehicle->model }}
                            ({{ $vehicle->capacity }} seats)
                        </option>
                        @endforeach
                    </select>
                    @error('vehicle_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group full">
                    <label class="form-label">Notes</label>
                    <textarea name="notes"
                        class="form-textarea {{ $errors->has('notes') ? 'error' : '' }}"
                        placeholder="Any special instructions for the driver…">{{ old('notes') }}</textarea>
                    @error('notes') <p class="form-error">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>
    </div>

    {{-- Summary preview --}}
    <div class="card" id="summaryCard" style="margin-bottom:20px;display:none;">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-clipboard-check-fill"></i> Trip Summary</div>
        </div>
        <div class="card-body" style="padding:0;">
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0;">
                <div style="padding:16px 20px;border-right:1px solid rgba(255,255,255,.05);">
                    <div style="font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:6px;">Route</div>
                    <div style="font-size:.9rem;font-weight:600;color:var(--white);" id="previewRoute">—</div>
                </div>
                <div style="padding:16px 20px;border-right:1px solid rgba(255,255,255,.05);">
                    <div style="font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:6px;">Driver</div>
                    <div style="font-size:.9rem;font-weight:600;color:var(--white);" id="previewDriver">—</div>
                </div>
                <div style="padding:16px 20px;">
                    <div style="font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:6px;">Vehicle</div>
                    <div style="font-size:.9rem;font-weight:600;color:var(--white);" id="previewVehicle">—</div>
                </div>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:12px;justify-content:flex-end;">
        <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-calendar-check-fill"></i> Schedule Trip
        </button>
    </div>

</form>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.querySelector('.flash-error');
        if (el) el.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    });
</script>
@endif

@push('scripts')
<script>
    // ── Auto-fill vehicle when driver is selected ─────────────────────────────────
    function syncVehicle() {
        var driverSelect = document.getElementById('driverSelect');
        var vehicleSelect = document.getElementById('vehicleSelect');
        var selected = driverSelect.options[driverSelect.selectedIndex];
        var vehicleId = selected ? selected.dataset.vehicle : null;

        if (vehicleId) {
            for (var i = 0; i < vehicleSelect.options.length; i++) {
                if (vehicleSelect.options[i].value == vehicleId) {
                    vehicleSelect.selectedIndex = i;
                    break;
                }
            }
        }
        updatePreview();
    }

    // ── Show schedule hint when route is chosen ───────────────────────────────────
    function updateScheduleHint() {
        var sel = document.getElementById('routeSelect');
        var opt = sel.options[sel.selectedIndex];
        var hint = document.getElementById('scheduleHint');
        var hintText = document.getElementById('scheduleHintText');
        var morning = opt ? opt.dataset.morning : '';
        var afternoon = opt ? opt.dataset.afternoon : '';

        if (morning || afternoon) {
            var parts = [];
            if (morning) parts.push('Morning: ' + morning);
            if (afternoon) parts.push('Afternoon: ' + afternoon);
            hintText.textContent = 'Scheduled times for this route — ' + parts.join(' · ');
            hint.style.display = 'flex';
        } else {
            hint.style.display = 'none';
        }
        updatePreview();
    }

    // ── Live summary preview ──────────────────────────────────────────────────────
    function updatePreview() {
        var routeSel = document.getElementById('routeSelect');
        var driverSel = document.getElementById('driverSelect');
        var vehicleSel = document.getElementById('vehicleSelect');
        var card = document.getElementById('summaryCard');

        var routeText = routeSel.options[routeSel.selectedIndex]?.text || '—';
        var driverText = driverSel.options[driverSel.selectedIndex]?.text || '—';
        var vehicleText = vehicleSel.options[vehicleSel.selectedIndex]?.text || '—';

        var hasAll = routeSel.value && driverSel.value && vehicleSel.value;
        card.style.display = hasAll ? 'block' : 'none';

        document.getElementById('previewRoute').textContent = routeText.split(' (')[0].trim();
        document.getElementById('previewDriver').textContent = driverText.split(' (')[0].trim();
        document.getElementById('previewVehicle').textContent = vehicleText.split('(')[0].trim();
    }

    // Wire up vehicle select change to preview
    document.getElementById('vehicleSelect').addEventListener('change', updatePreview);

    // Run on load in case old() repopulated values
    document.addEventListener('DOMContentLoaded', function() {
        updateScheduleHint();
        syncVehicle();
        updatePreview();
    });
</script>
@endpush

@endsection