@extends('layouts.admin')
@section('page-title', 'Students')
@section('breadcrumb', 'Students')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Students</h1>
        <p>All enrolled students and their parent accounts.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Enroll Student
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 22px;">
        <form method="GET" action="{{ route('admin.students.index') }}"
              style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:220px;">
                <label class="form-label">Search</label>
                <div style="position:relative;">
                    <input type="text" name="search" class="form-input"
                           value="{{ request('search') }}"
                           placeholder="Search by student name…"
                           style="padding-left:36px;">
                    <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.85rem;pointer-events:none;"></i>
                </div>
            </div>
            <div style="min-width:200px;">
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
            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                @if(request()->hasAny(['search', 'route_id']))
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        @if($students->isEmpty())
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <h3>No students found</h3>
                <p>Try adjusting your filters or enroll a new student.</p>
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Grade</th>
                    <th>Parent</th>
                    <th>Route</th>
                    <th>Boarding Stop</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td>
                        <div class="avatar-row">
                            <img src="{{ $student->photo_url }}" class="avatar-sm" alt="">
                            <div>
                                <div class="avatar-name">{{ $student->full_name }}</div>
                                @if($student->student_id)
                                    <div class="avatar-sub">#{{ $student->student_id }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-secondary">{{ $student->display_grade ?: '—' }}</span>
                    </td>
                    <td>
                        <div class="avatar-name" style="font-size:.85rem;">{{ $student->parent_name }}</div>
                        <div class="td-muted">{{ $student->parent_phone }}</div>
                    </td>
                    <td>
                        @if($student->route)
                            <div style="display:flex;align-items:center;gap:6px;">
                                <i class="bi bi-signpost-2" style="color:var(--teal);font-size:.85rem;"></i>
                                {{ $student->route->name }}
                            </div>
                        @else
                            <span class="td-muted">— Not assigned</span>
                        @endif
                    </td>
                    <td>
                        @if($student->stop)
                            <div style="display:flex;align-items:center;gap:6px;">
                                <i class="bi bi-geo-alt-fill" style="color:var(--gold);font-size:.85rem;"></i>
                                {{ $student->stop->name }}
                            </div>
                        @else
                            <span class="td-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($student->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.students.destroy', $student) }}"
                                  onsubmit="return confirm('Remove this student record?')">
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

{{ $students->links('partials.pagination') }}

@endsection