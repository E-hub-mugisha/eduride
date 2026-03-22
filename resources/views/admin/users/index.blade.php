@extends('layouts.admin')
@section('page-title', 'Users')
@section('breadcrumb', 'Users')

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
        <h1>User Management</h1>
        <p>Admins, drivers, and parent accounts.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add User
    </a>
</div>

{{-- Role filter tabs --}}
<div style="display:flex;gap:6px;margin-bottom:20px;flex-wrap:wrap;">
    @foreach([
        ['',       'All',     $counts['all'],    'bi-people-fill'],
        ['admin',  'Admins',  $counts['admin'],  'bi-shield-fill'],
        ['driver', 'Drivers', $counts['driver'], 'bi-person-badge-fill'],
        ['parent', 'Parents', $counts['parent'], 'bi-people-fill'],
    ] as [$role, $label, $count, $icon])
    <a href="{{ route('admin.users.index', array_filter(['role' => $role, 'search' => request('search')])) }}"
       style="display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:10px;font-family:var(--font-d);font-size:.8rem;font-weight:700;text-decoration:none;transition:all .2s;
              {{ request('role', '') === $role
                 ? 'background:rgba(0,229,195,.12);color:var(--teal);border:1px solid rgba(0,229,195,.25);'
                 : 'background:var(--navy-3);color:var(--muted);border:1px solid rgba(255,255,255,.07);' }}">
        <i class="bi {{ $icon }}" style="font-size:.85rem;"></i>
        {{ $label }}
        <span style="background:rgba(255,255,255,.1);padding:1px 7px;border-radius:20px;font-size:.68rem;">{{ $count }}</span>
    </a>
    @endforeach
</div>

{{-- Search + filter bar --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:14px 20px;">
        <form method="GET" action="{{ route('admin.users.index') }}"
              style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            @if(request('role'))
                <input type="hidden" name="role" value="{{ request('role') }}">
            @endif
            <div style="flex:1;min-width:220px;position:relative;">
                <input type="text" name="search" class="form-input"
                       value="{{ request('search') }}"
                       placeholder="Search by name, email or phone…"
                       style="padding-left:36px;">
                <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:.85rem;pointer-events:none;"></i>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-funnel"></i> Search
            </button>
            @if(request()->hasAny(['search','role']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Clear</a>
            @endif
        </form>
    </div>
</div>

{{-- Users table --}}
<div class="card">
    <div class="table-wrap">
        @if($users->isEmpty())
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <h3>No users found</h3>
                <p>Try adjusting your search or filter.</p>
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    {{-- User --}}
                    <td>
                        <div class="avatar-row">
                            <img src="{{ $user->avatar_url }}" class="avatar-sm" alt="">
                            <div>
                                <div class="avatar-name">{{ $user->name }}</div>
                                <div class="avatar-sub">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Role badge --}}
                    <td>
                        @php
                            $roleColor = match($user->role) {
                                'admin'  => 'danger',
                                'driver' => 'info',
                                'parent' => 'success',
                                default  => 'secondary',
                            };
                            $roleIcon = match($user->role) {
                                'admin'  => 'bi-shield-fill',
                                'driver' => 'bi-person-badge-fill',
                                'parent' => 'bi-people-fill',
                                default  => 'bi-person',
                            };
                        @endphp
                        <span class="badge badge-{{ $roleColor }}">
                            <i class="bi {{ $roleIcon }}" style="font-size:.65rem;"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>

                    {{-- Phone --}}
                    <td class="td-muted">{{ $user->phone ?? '—' }}</td>

                    {{-- Role-specific details --}}
                    <td>
                        @if($user->role === 'driver' && $user->driver)
                            <div style="font-size:.8rem;color:var(--white);">{{ $user->driver->license_number }}</div>
                            <div class="avatar-sub">
                                {{ $user->driver->vehicle?->plate_number ?? 'No vehicle' }}
                                @if($user->driver->is_license_expired)
                                    · <span style="color:var(--danger);">License expired</span>
                                @endif
                            </div>
                        @elseif($user->role === 'parent')
                            <div style="font-size:.8rem;color:var(--white);">{{ $user->students_count }} child{{ $user->students_count != 1 ? 'ren' : '' }}</div>
                            <div class="avatar-sub">{{ $user->notifications_count }} notifications</div>
                        @elseif($user->role === 'admin')
                            <div class="avatar-sub">Administrator</div>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td>
                        @if($user->id === auth()->id())
                            <span class="badge badge-teal">You</span>
                        @elseif($user->email_verified_at)
                            <span class="badge badge-success"><i class="bi bi-check-circle-fill" style="font-size:.65rem;"></i> Active</span>
                        @else
                            <span class="badge badge-secondary"><i class="bi bi-dash-circle" style="font-size:.65rem;"></i> Inactive</span>
                        @endif
                    </td>

                    {{-- Joined --}}
                    <td class="td-muted">{{ $user->created_at->format('d M Y') }}</td>

                    {{-- Actions --}}
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>

                            @if($user->id !== auth()->id())
                            {{-- Toggle active --}}
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm btn-icon {{ $user->email_verified_at ? 'btn-secondary' : 'btn-danger' }}"
                                        title="{{ $user->email_verified_at ? 'Deactivate' : 'Activate' }}"
                                        onclick="return confirm('{{ $user->email_verified_at ? 'Deactivate' : 'Activate' }} this user?')">
                                    <i class="bi bi-{{ $user->email_verified_at ? 'toggle-on' : 'toggle-off' }}"></i>
                                </button>
                            </form>

                            {{-- Delete --}}
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('Permanently delete {{ $user->name }}? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

{{ $users->appends(request()->query())->links('partials.pagination') }}

@endsection