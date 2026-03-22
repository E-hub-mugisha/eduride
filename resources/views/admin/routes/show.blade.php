@extends('layouts.admin')
@section('page-title', $route->name)
@section('breadcrumb', 'Routes / ' . $route->name)

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

{{-- Validation errors (add-stop form) --}}
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
        <h1>{{ $route->name }}</h1>
        <p>{{ $route->path_summary }}</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.routes.edit', $route) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit Route
        </a>
        <a href="{{ route('admin.routes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">

    {{-- Route info --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-signpost-2-fill"></i> Route Info</div>
            <span class="badge badge-{{ $route->is_active ? 'success' : 'secondary' }}">
                {{ $route->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div class="card-body" style="padding:0;">
            @foreach([
                ['Type',                $route->type_label],
                ['Morning Departure',   $route->morning_departure   ?? '—'],
                ['Afternoon Departure', $route->afternoon_departure ?? '—'],
                ['Est. Duration',       $route->estimated_duration_min ? $route->estimated_duration_min . ' min' : '—'],
                ['Distance',            $route->total_distance_km ? $route->total_distance_km . ' km' : '—'],
                ['Total Stops',         $route->stop_count],
                ['Students Enrolled',   $route->student_count],
            ] as [$label, $value])
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 20px;border-bottom:1px solid rgba(255,255,255,.04);">
                <span style="font-size:.82rem;color:var(--muted);">{{ $label }}</span>
                <span style="font-size:.88rem;font-weight:500;color:var(--white);">{{ $value }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Recent trips --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-clock-history"></i> Recent Trips</div>
        </div>
        <div class="card-body" style="padding:8px;">
            @forelse($route->trips->take(5) as $trip)
            <div style="display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:10px;border-bottom:1px solid rgba(255,255,255,.04);">
                <div style="flex:1;">
                    <div style="font-size:.83rem;font-weight:600;color:var(--white);">{{ $trip->driver->name }}</div>
                    <div style="font-size:.72rem;color:var(--muted);">{{ $trip->scheduled_at?->format('d M H:i') ?? '—' }}</div>
                </div>
                <span class="badge badge-{{ $trip->status_badge['color'] }}">{{ $trip->status_badge['label'] }}</span>
            </div>
            @empty
                <div class="empty-state" style="padding:20px 0;">
                    <i class="bi bi-calendar-x"></i><p>No trips yet</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Stops management --}}
    <div class="card" style="grid-column:1/-1;">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-geo-alt-fill"></i> Stops ({{ $route->stops->count() }})</div>
        </div>

        {{-- Add stop form --}}
        <div style="padding:16px 20px;border-bottom:1px solid rgba(255,255,255,.05);background:rgba(0,229,195,.02);">
            <div style="font-size:.75rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--teal);margin-bottom:14px;">
                <i class="bi bi-plus-circle-fill"></i> Add Stop
            </div>
            <form method="POST" action="{{ route('admin.routes.stops.store', $route) }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Stop Name <span class="req">*</span></label>
                        <input type="text" name="name"
                               class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                               value="{{ old('name') }}"
                               placeholder="e.g. Kicukiro Centre" required>
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Landmark</label>
                        <input type="text" name="landmark"
                               class="form-input {{ $errors->has('landmark') ? 'error' : '' }}"
                               value="{{ old('landmark') }}"
                               placeholder="Near market / clinic">
                        @error('landmark') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Latitude <span class="req">*</span></label>
                        <input type="number" name="latitude"
                               class="form-input {{ $errors->has('latitude') ? 'error' : '' }}"
                               value="{{ old('latitude') }}"
                               step="any" placeholder="-1.9706" required>
                        @error('latitude') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Longitude <span class="req">*</span></label>
                        <input type="number" name="longitude"
                               class="form-input {{ $errors->has('longitude') ? 'error' : '' }}"
                               value="{{ old('longitude') }}"
                               step="any" placeholder="30.1050" required>
                        @error('longitude') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Arrival Offset (min) <span class="req">*</span></label>
                        <input type="number" name="arrival_offset_min"
                               class="form-input {{ $errors->has('arrival_offset_min') ? 'error' : '' }}"
                               value="{{ old('arrival_offset_min', 0) }}"
                               min="0" required>
                        <p class="form-hint">Minutes after departure to reach this stop.</p>
                        @error('arrival_offset_min') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Dwell Time (sec)</label>
                        <input type="number" name="dwell_time_sec"
                               class="form-input {{ $errors->has('dwell_time_sec') ? 'error' : '' }}"
                               value="{{ old('dwell_time_sec', 30) }}"
                               min="0">
                        @error('dwell_time_sec') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;margin-top:4px;">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg"></i> Add Stop
                    </button>
                </div>
            </form>
        </div>

        {{-- Stops list --}}
        <div class="table-wrap">
            @if($route->stops->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-geo-alt"></i>
                    <h3>No stops yet</h3>
                    <p>Add the first stop using the form above.</p>
                </div>
            @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Stop Name</th>
                        <th>Landmark</th>
                        <th>Coordinates</th>
                        <th>Offset</th>
                        <th>Students</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($route->stops as $stop)
                    <tr>
                        <td>
                            <span style="width:26px;height:26px;border-radius:50%;background:rgba(0,229,195,.1);border:1px solid rgba(0,229,195,.2);display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:var(--teal);">
                                {{ $stop->order }}
                            </span>
                        </td>
                        <td style="font-weight:600;">{{ $stop->name }}</td>
                        <td class="td-muted">{{ $stop->landmark ?? '—' }}</td>
                        <td style="font-family:monospace;font-size:.78rem;color:var(--muted);">
                            {{ $stop->latitude }}, {{ $stop->longitude }}
                        </td>
                        <td class="td-muted">+{{ $stop->arrival_offset_min }} min</td>
                        <td class="td-muted">{{ $stop->students->count() }}</td>
                        <td>
                            <form method="POST"
                                  action="{{ route('admin.routes.stops.destroy', [$route, $stop]) }}"
                                  onsubmit="return confirm('Remove this stop? Students assigned to it will lose their boarding stop.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Remove stop">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    {{-- Students on this route --}}
    @if($route->students->isNotEmpty())
    <div class="card" style="grid-column:1/-1;">
        <div class="card-header">
            <div class="card-title">
                <i class="bi bi-people-fill"></i> Enrolled Students ({{ $route->students->count() }})
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Grade</th>
                        <th>Boarding Stop</th>
                        <th>Parent</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($route->students as $student)
                    <tr>
                        <td>
                            <div class="avatar-row">
                                <img src="{{ $student->photo_url }}" class="avatar-sm" alt="">
                                <span class="avatar-name">{{ $student->full_name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-secondary">{{ $student->display_grade ?: '—' }}</span>
                        </td>
                        <td class="td-muted">{{ $student->stop?->name ?? '—' }}</td>
                        <td class="td-muted">{{ $student->parent_name }}</td>
                        <td class="td-muted">{{ $student->parent_phone }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

{{-- Scroll to errors on page load --}}
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.querySelector('.flash-error');
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });

        // Re-open the add-stop form so the user can see which field failed
        var form = document.querySelector('[name="name"]');
        if (form) form.focus();
    });
</script>
@endif

@endsection