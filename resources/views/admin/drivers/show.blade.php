@extends('layouts.admin')
@section('page-title', $driver->name)
@section('breadcrumb', 'Drivers / ' . $driver->name)

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>{{ $driver->name }}</h1>
        <p>License: {{ $driver->license_number }}</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.drivers.edit', $driver) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">

    {{-- Profile --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-person-badge-fill"></i> Driver Profile</div>
            <span class="badge badge-{{ $driver->status_badge['color'] }}">{{ $driver->status_badge['label'] }}</span>
        </div>
        <div class="card-body">
            <div class="avatar-row" style="margin-bottom:20px;">
                <img src="{{ $driver->avatar_url }}" style="width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid rgba(0,229,195,.25);" alt="">
                <div>
                    <div style="font-family:var(--font-d);font-size:1.05rem;font-weight:700;color:var(--white);">{{ $driver->name }}</div>
                    <div style="font-size:.8rem;color:var(--muted);">{{ $driver->user->email }}</div>
                </div>
            </div>
            @foreach([
                ['Phone',          $driver->phone ?: '—'],
                ['License No.',    $driver->license_number],
                ['License Expiry', $driver->license_expiry?->format('d M Y') ?? '—'],
                ['Total Trips',    $driver->total_trips],
            ] as [$label, $value])
            <div style="display:flex;justify-content:space-between;align-items:center;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.04);">
                <span style="font-size:.82rem;color:var(--muted);">{{ $label }}</span>
                <span style="font-size:.88rem;font-weight:500;color:var(--white);">{{ $value }}</span>
            </div>
            @endforeach

            @if($driver->is_license_expired)
                <div class="flash flash-error" style="margin-top:14px;">
                    <i class="bi bi-exclamation-circle-fill"></i> License has expired.
                </div>
            @elseif($driver->is_license_expiring_soon)
                <div style="background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.2);color:var(--warning);padding:10px 14px;border-radius:10px;font-size:.82rem;margin-top:14px;display:flex;gap:8px;align-items:center;">
                    <i class="bi bi-exclamation-triangle-fill"></i> License expiring within 30 days.
                </div>
            @endif
        </div>
    </div>

    {{-- Assigned vehicle --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-truck-front-fill"></i> Assigned Vehicle</div>
        </div>
        <div class="card-body">
            @if($driver->vehicle)
                @foreach([
                    ['Plate',    $driver->vehicle->plate_number],
                    ['Brand',    $driver->vehicle->brand ?? '—'],
                    ['Model',    $driver->vehicle->model],
                    ['Capacity', $driver->vehicle->capacity . ' seats'],
                    ['Status',   $driver->vehicle->status_badge['label']],
                ] as [$label, $value])
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.04);">
                    <span style="font-size:.82rem;color:var(--muted);">{{ $label }}</span>
                    <span style="font-size:.88rem;font-weight:500;color:var(--white);">{{ $value }}</span>
                </div>
                @endforeach
                <a href="{{ route('admin.vehicles.show', $driver->vehicle) }}" class="btn btn-secondary btn-sm" style="margin-top:14px;">
                    <i class="bi bi-eye"></i> View Vehicle
                </a>
            @else
                <div class="empty-state" style="padding:24px 0;">
                    <i class="bi bi-truck-front"></i>
                    <h3>No vehicle assigned</h3>
                    <p>Edit this driver to assign a vehicle.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent trips --}}
    <div class="card" style="grid-column:1/-1;">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-clock-history"></i> Recent Trips</div>
        </div>
        <div class="table-wrap">
            @if($driver->trips->isEmpty())
                <div class="empty-state"><i class="bi bi-calendar-x"></i><h3>No trips yet</h3></div>
            @else
            <table>
                <thead>
                    <tr><th>Route</th><th>Vehicle</th><th>Type</th><th>Date</th><th>Duration</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($driver->trips as $trip)
                    <tr>
                        <td style="font-weight:600;">{{ $trip->route->name }}</td>
                        <td class="td-muted">{{ $trip->vehicle->plate_number }}</td>
                        <td class="td-muted">{{ ucfirst($trip->type) }}</td>
                        <td class="td-muted">{{ $trip->scheduled_at?->format('d M Y H:i') ?? '—' }}</td>
                        <td class="td-muted">{{ $trip->duration ?? '—' }}</td>
                        <td><span class="badge badge-{{ $trip->status_badge['color'] }}">{{ $trip->status_badge['label'] }}</span></td>
                        <td>
                            <a href="{{ route('admin.trips.show', $trip) }}" class="btn btn-secondary btn-sm btn-icon">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

</div>
@endsection