@extends('layouts.admin')
@section('page-title', 'Drivers')
@section('breadcrumb', 'Drivers')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Drivers</h1>
        <p>Manage registered drivers and their vehicle assignments.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Driver
        </a>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        @if($drivers->isEmpty())
            <div class="empty-state">
                <i class="bi bi-person-badge"></i>
                <h3>No drivers registered</h3>
                <p>Add your first driver to begin assigning routes.</p>
                <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary" style="margin-top:16px;">Add Driver</a>
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>License</th>
                    <th>Vehicle</th>
                    <th>Status</th>
                    <th>Trips</th>
                    <th>License Expiry</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($drivers as $driver)
                <tr>
                    <td>
                        <div class="avatar-row">
                            <img src="{{ $driver->avatar_url }}" class="avatar-sm" alt="">
                            <div>
                                <div class="avatar-name">{{ $driver->name }}</div>
                                <div class="avatar-sub">{{ $driver->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-family:monospace;font-size:.82rem;">{{ $driver->license_number }}</span>
                    </td>
                    <td>
                        @if($driver->vehicle)
                            <div style="display:flex;align-items:center;gap:6px;">
                                <i class="bi bi-bus-front-fill" style="color:var(--info);font-size:.85rem;"></i>
                                <span>{{ $driver->vehicle->plate_number }}</span>
                            </div>
                            <div class="td-muted">{{ $driver->vehicle->model }}</div>
                        @else
                            <span class="td-muted">— Unassigned</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $driver->status_badge['color'] }}">
                            @if($driver->status === 'on_trip') <span class="live-dot" style="width:6px;height:6px;"></span> @endif
                            {{ $driver->status_badge['label'] }}
                        </span>
                    </td>
                    <td class="td-muted">{{ $driver->trips_count }}</td>
                    <td>
                        @if($driver->license_expiry)
                            <span class="{{ $driver->is_license_expired ? 'badge badge-danger' : ($driver->is_license_expiring_soon ? 'badge badge-warning' : '') }}">
                                {{ $driver->license_expiry->format('d M Y') }}
                            </span>
                        @else
                            <span class="td-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.drivers.show', $driver) }}" class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.drivers.edit', $driver) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.drivers.destroy', $driver) }}"
                                  onsubmit="return confirm('Remove this driver? Their user account will also be deleted.')">
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

{{ $drivers->links('partials.pagination') }}

@endsection