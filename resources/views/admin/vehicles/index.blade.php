@extends('layouts.admin')
@section('page-title', 'Vehicles')
@section('breadcrumb', 'Vehicles')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Fleet Vehicles</h1>
        <p>Manage all school buses and transport vehicles.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Vehicle
        </a>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        @if($vehicles->isEmpty())
            <div class="empty-state">
                <i class="bi bi-truck-front"></i>
                <h3>No vehicles yet</h3>
                <p>Add your first vehicle to get started.</p>
                <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary" style="margin-top:16px;">Add Vehicle</a>
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Plate</th>
                    <th>Capacity</th>
                    <th>Driver</th>
                    <th>Status</th>
                    <th>Trips</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicles as $vehicle)
                <tr>
                    <td>
                        <div class="avatar-row">
                            <div style="width:38px;height:38px;border-radius:10px;background:rgba(99,179,237,.1);display:flex;align-items:center;justify-content:center;color:var(--info);font-size:1rem;flex-shrink:0;">
                                <i class="bi bi-bus-front-fill"></i>
                            </div>
                            <div>
                                <div class="avatar-name">{{ $vehicle->brand }} {{ $vehicle->model }}</div>
                                <div class="avatar-sub">{{ $vehicle->color }} · {{ $vehicle->year_manufactured }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-family:monospace;background:rgba(255,255,255,.06);padding:4px 10px;border-radius:6px;font-size:.82rem;letter-spacing:.05em;">
                            {{ $vehicle->plate_number }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <i class="bi bi-people" style="color:var(--muted);font-size:.85rem;"></i>
                            <span>{{ $vehicle->capacity }} seats</span>
                        </div>
                    </td>
                    <td>
                        @if($vehicle->driver)
                            <div class="avatar-row">
                                <img src="{{ $vehicle->driver->avatar_url }}" class="avatar-sm" alt="">
                                <span class="avatar-name">{{ $vehicle->driver->name }}</span>
                            </div>
                        @else
                            <span class="td-muted">— Unassigned</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $vehicle->status_badge['color'] }}">
                            {{ $vehicle->status_badge['label'] }}
                        </span>
                    </td>
                    <td class="td-muted">{{ $vehicle->trips_count }}</td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.vehicles.show', $vehicle) }}" class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.vehicles.destroy', $vehicle) }}"
                                  onsubmit="return confirm('Delete this vehicle?')">
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

{{ $vehicles->links('partials.pagination') }}

@endsection