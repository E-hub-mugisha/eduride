@extends('layouts.admin')
@section('page-title', $vehicle->plate_number)
@section('breadcrumb', 'Vehicles / ' . $vehicle->plate_number)

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>{{ $vehicle->brand }} {{ $vehicle->model }}</h1>
        <p>{{ $vehicle->plate_number }} · {{ $vehicle->color }} · {{ $vehicle->year_manufactured }}</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">

    {{-- Details --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-truck-front-fill"></i> Vehicle Details</div>
            <span class="badge badge-{{ $vehicle->status_badge['color'] }}">{{ $vehicle->status_badge['label'] }}</span>
        </div>
        <div class="card-body" style="padding:0;">
            @foreach([
                ['Plate Number',      $vehicle->plate_number],
                ['Brand',             $vehicle->brand ?? '—'],
                ['Model',             $vehicle->model],
                ['Color',             $vehicle->color ?? '—'],
                ['Seating Capacity',  $vehicle->capacity . ' seats'],
                ['Year Manufactured', $vehicle->year_manufactured ?? '—'],
                ['Status',            $vehicle->status_badge['label']],
            ] as [$label, $value])
            <div style="display:flex;justify-content:space-between;align-items:center;padding:13px 20px;border-bottom:1px solid rgba(255,255,255,.04);">
                <span style="font-size:.82rem;color:var(--muted);">{{ $label }}</span>
                <span style="font-size:.88rem;font-weight:500;color:var(--white);">{{ $value }}</span>
            </div>
            @endforeach
            @if($vehicle->notes)
            <div style="padding:14px 20px;">
                <div style="font-size:.78rem;color:var(--muted);margin-bottom:6px;">Notes</div>
                <div style="font-size:.85rem;color:var(--white);">{{ $vehicle->notes }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Assigned driver --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-person-badge-fill"></i> Assigned Driver</div>
        </div>
        <div class="card-body">
            @if($vehicle->driver)
                <div class="avatar-row" style="margin-bottom:16px;">
                    <img src="{{ $vehicle->driver->avatar_url }}" class="avatar-sm" style="width:48px;height:48px;" alt="">
                    <div>
                        <div class="avatar-name" style="font-size:.95rem;">{{ $vehicle->driver->name }}</div>
                        <div class="avatar-sub">{{ $vehicle->driver->user->email }}</div>
                        <div class="avatar-sub">{{ $vehicle->driver->phone }}</div>
                    </div>
                </div>
                <div style="display:flex;gap:10px;">
                    <a href="{{ route('admin.drivers.show', $vehicle->driver) }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-eye"></i> View Driver
                    </a>
                    @if($vehicle->driver->phone)
                    <a href="tel:{{ $vehicle->driver->phone }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-telephone"></i> Call
                    </a>
                    @endif
                </div>
            @else
                <div class="empty-state" style="padding:24px 0;">
                    <i class="bi bi-person-slash"></i>
                    <h3>No driver assigned</h3>
                    <p>Edit this vehicle to assign a driver.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent trips --}}
    <div class="card" style="grid-column:1/-1;">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-clock-history"></i> Recent Trips</div>
            <span style="font-size:.78rem;color:var(--muted);">Last 10</span>
        </div>
        <div class="table-wrap">
            @if($vehicle->trips->isEmpty())
                <div class="empty-state"><i class="bi bi-calendar-x"></i><h3>No trips yet</h3></div>
            @else
            <table>
                <thead>
                    <tr>
                        <th>Route</th><th>Driver</th><th>Type</th><th>Date</th><th>Status</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicle->trips as $trip)
                    <tr>
                        <td style="font-weight:600;">{{ $trip->route->name }}</td>
                        <td>{{ $trip->driver->name }}</td>
                        <td class="td-muted">{{ ucfirst($trip->type) }}</td>
                        <td class="td-muted">{{ $trip->scheduled_at?->format('d M Y H:i') ?? '—' }}</td>
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