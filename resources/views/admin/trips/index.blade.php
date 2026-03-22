@extends('layouts.admin')
@section('page-title', 'Trips')
@section('breadcrumb', 'Trips')

@section('content')

{{-- Flash --}}
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

<div class="page-header">
    <div class="page-header-left">
        <h1>Trips</h1>
        <p>Full history of all transport trips.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.trips.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Schedule Trip
        </a>
        <a href="{{ route('admin.map') }}" class="btn btn-secondary">
            <i class="bi bi-map-fill"></i> Live Map
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('admin.trips.index') }}"
              style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            <div style="min-width:180px;">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $s)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="min-width:180px;">
                <label class="form-label">Route</label>
                <select name="route_id" class="form-select">
                    <option value="">All routes</option>
                    @foreach($routes as $route)
                        <option value="{{ $route->id }}" {{ request('route_id') == $route->id ? 'selected' : '' }}>
                            {{ $route->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="min-width:160px;">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-input" value="{{ request('date') }}">
            </div>
            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                @if(request()->hasAny(['status', 'route_id', 'date']))
                    <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary btn-sm">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        @if($trips->isEmpty())
            <div class="empty-state">
                <i class="bi bi-calendar-x"></i>
                <h3>No trips found</h3>
                <p>
                    @if(request()->hasAny(['status', 'route_id', 'date']))
                        Try adjusting your filters.
                    @else
                        No trips yet. <a href="{{ route('admin.trips.create') }}" style="color:var(--teal);">Schedule the first one →</a>
                    @endif
                </p>
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>Route</th>
                    <th>Driver</th>
                    <th>Vehicle</th>
                    <th>Type</th>
                    <th>Scheduled</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trips as $trip)
                <tr>
                    <td style="font-weight:600;">{{ $trip->route->name }}</td>
                    <td>
                        <div class="avatar-row">
                            <img src="{{ $trip->driver->avatar_url }}" class="avatar-sm" alt="">
                            <span class="avatar-name">{{ $trip->driver->name }}</span>
                        </div>
                    </td>
                    <td class="td-muted">{{ $trip->vehicle->plate_number }}</td>
                    <td class="td-muted">{{ ucfirst($trip->type) }}</td>
                    <td class="td-muted">{{ $trip->scheduled_at?->format('d M Y H:i') ?? '—' }}</td>
                    <td class="td-muted">{{ $trip->duration ?? '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $trip->status_badge['color'] }}">
                            @if($trip->status === 'in_progress')
                                <span class="live-dot" style="width:6px;height:6px;"></span>
                            @endif
                            {{ $trip->status_badge['label'] }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            @if($trip->isInProgress())
                            <a href="{{ route('admin.trips.track', $trip) }}"
                               class="btn btn-primary btn-sm btn-icon" title="Live Track">
                                <i class="bi bi-geo-alt-fill"></i>
                            </a>
                            @endif
                            <a href="{{ route('admin.trips.show', $trip) }}"
                               class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.trips.destroy', $trip) }}"
                                  onsubmit="return confirm('Delete this trip record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

{{ $trips->links('partials.pagination') }}

@endsection