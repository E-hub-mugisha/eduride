@extends('layouts.admin')
@section('page-title', 'Routes')
@section('breadcrumb', 'Routes')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Transport Routes</h1>
        <p>Define pickup routes and GPS stops for your fleet.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.routes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> New Route
        </a>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        @if($routes->isEmpty())
            <div class="empty-state">
                <i class="bi bi-signpost-2"></i>
                <h3>No routes configured</h3>
                <p>Create your first route to start assigning drivers and students.</p>
                <a href="{{ route('admin.routes.create') }}" class="btn btn-primary" style="margin-top:16px;">Create Route</a>
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>Route Name</th>
                    <th>Type</th>
                    <th>Schedule</th>
                    <th>Stops</th>
                    <th>Students</th>
                    <th>Live</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($routes as $route)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $route->name }}</div>
                        <div class="td-muted">{{ Str::limit($route->description, 45) }}</div>
                    </td>
                    <td>
                        <span class="badge badge-teal">
                            <i class="bi bi-{{ $route->type === 'both' ? 'arrow-left-right' : ($route->type === 'morning' ? 'sunrise' : 'sunset') }}"></i>
                            {{ $route->type_label }}
                        </span>
                    </td>
                    <td>
                        <div style="font-size:.82rem;">
                            @if($route->morning_departure)
                                <div style="display:flex;align-items:center;gap:5px;">
                                    <i class="bi bi-sunrise" style="color:var(--gold);font-size:.8rem;"></i>
                                    {{ $route->morning_departure }}
                                </div>
                            @endif
                            @if($route->afternoon_departure)
                                <div style="display:flex;align-items:center;gap:5px;">
                                    <i class="bi bi-sunset" style="color:var(--info);font-size:.8rem;"></i>
                                    {{ $route->afternoon_departure }}
                                </div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <i class="bi bi-geo-alt" style="color:var(--teal);font-size:.85rem;"></i>
                            {{ $route->stops_count }}
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <i class="bi bi-people" style="color:var(--gold);font-size:.85rem;"></i>
                            {{ $route->students_count }}
                        </div>
                    </td>
                    <td>
                        @if($route->trips->isNotEmpty())
                            <span class="live-dot"></span>
                        @else
                            <span class="td-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($route->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.routes.show', $route) }}" class="btn btn-secondary btn-sm btn-icon" title="View & Stops">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.routes.edit', $route) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.routes.destroy', $route) }}"
                                  onsubmit="return confirm('Delete this route?')">
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

{{ $routes->links('partials.pagination') }}

@endsection