@extends('layouts.admin')
@section('page-title', $user->name)
@section('breadcrumb', 'Users / ' . $user->name)

@section('content')

@if(session('success'))
    <div class="flash flash-success" style="margin-bottom:20px;">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

<div class="page-header">
    <div class="page-header-left">
        <h1>{{ $user->name }}</h1>
        <p>{{ ucfirst($user->role) }} account</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:340px 1fr;gap:20px;align-items:start;">

    {{-- ── Left: profile card ── --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        <div class="card">
            <div class="card-body" style="text-align:center;padding:28px 20px;">
                <img src="{{ $user->avatar_url }}"
                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid rgba(0,229,195,.25);margin-bottom:14px;" alt="">
                <div style="font-family:var(--font-d);font-size:1.05rem;font-weight:800;color:var(--white);margin-bottom:4px;">
                    {{ $user->name }}
                </div>
                <div style="font-size:.8rem;color:var(--muted);margin-bottom:12px;">{{ $user->email }}</div>
                @php
                    $roleColor = match($user->role) { 'admin'=>'danger','driver'=>'info','parent'=>'success', default=>'secondary' };
                    $roleIcon  = match($user->role) { 'admin'=>'bi-shield-fill','driver'=>'bi-person-badge-fill','parent'=>'bi-people-fill', default=>'bi-person' };
                @endphp
                <span class="badge badge-{{ $roleColor }}" style="font-size:.72rem;padding:5px 14px;">
                    <i class="bi {{ $roleIcon }}"></i> {{ ucfirst($user->role) }}
                </span>
                @if($user->email_verified_at)
                    <span class="badge badge-success" style="margin-left:6px;font-size:.72rem;padding:5px 14px;">Active</span>
                @else
                    <span class="badge badge-secondary" style="margin-left:6px;font-size:.72px;padding:5px 14px;">Inactive</span>
                @endif
            </div>
            <div style="border-top:1px solid rgba(255,255,255,.05);">
                @foreach([
                    ['bi-telephone',    'Phone',   $user->phone ?? '—'],
                    ['bi-calendar3',    'Joined',  $user->created_at->format('d M Y')],
                    ['bi-clock-history','Last',    $user->updated_at->diffForHumans()],
                ] as [$icon, $label, $value])
                <div style="display:flex;align-items:center;gap:10px;padding:12px 18px;border-bottom:1px solid rgba(255,255,255,.04);">
                    <i class="bi {{ $icon }}" style="color:var(--teal);font-size:.85rem;width:16px;text-align:center;flex-shrink:0;"></i>
                    <span style="font-size:.78rem;color:var(--muted);min-width:60px;">{{ $label }}</span>
                    <span style="font-size:.82rem;font-weight:500;color:var(--white);">{{ $value }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Driver details --}}
        @if($user->isDriver() && $user->driver)
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-card-text"></i> Driver Info</div>
                <span class="badge badge-{{ $user->driver->status_badge['color'] }}">{{ $user->driver->status_badge['label'] }}</span>
            </div>
            <div class="card-body" style="padding:0;">
                @foreach([
                    ['License No.',  $user->driver->license_number],
                    ['Expiry',       $user->driver->license_expiry?->format('d M Y') ?? '—'],
                    ['Vehicle',      $user->driver->vehicle?->plate_number ?? '— Not assigned'],
                    ['Total Trips',  $user->driver->total_trips],
                ] as [$label, $value])
                <div style="display:flex;justify-content:space-between;padding:11px 18px;border-bottom:1px solid rgba(255,255,255,.04);">
                    <span style="font-size:.8rem;color:var(--muted);">{{ $label }}</span>
                    <span style="font-size:.84rem;font-weight:600;color:var(--white);">{{ $value }}</span>
                </div>
                @endforeach
                @if($user->driver->is_license_expired)
                <div style="margin:12px 18px;padding:9px 12px;background:rgba(251,113,133,.08);border:1px solid rgba(251,113,133,.2);border-radius:9px;font-size:.78rem;color:var(--danger);display:flex;align-items:center;gap:7px;">
                    <i class="bi bi-exclamation-triangle-fill"></i> License has expired
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Reset password --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-key-fill"></i> Reset Password</div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
                    @csrf
                    <div class="form-group" style="margin-bottom:12px;">
                        <label class="form-label">New Password <span class="req">*</span></label>
                        <input type="password" name="password" class="form-input" placeholder="Min. 8 characters" required>
                    </div>
                    <div class="form-group" style="margin-bottom:16px;">
                        <label class="form-label">Confirm Password <span class="req">*</span></label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Repeat" required>
                    </div>
                    <button type="submit" class="btn btn-secondary btn-sm" style="width:100%;"
                            onclick="return confirm('Reset password for {{ $user->name }}?')">
                        <i class="bi bi-key"></i> Reset Password
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- ── Right: activity ── --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Children (parent role) --}}
        @if($user->isParent() && $user->students->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-people-fill"></i> Children ({{ $user->students->count() }})</div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Student</th><th>Grade</th><th>Route</th><th>Stop</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach($user->students as $student)
                        <tr>
                            <td>
                                <div class="avatar-row">
                                    <img src="{{ $student->photo_url }}" class="avatar-sm" alt="">
                                    <span class="avatar-name">{{ $student->full_name }}</span>
                                </div>
                            </td>
                            <td><span class="badge badge-secondary">{{ $student->display_grade ?: '—' }}</span></td>
                            <td class="td-muted">{{ $student->route?->name ?? '—' }}</td>
                            <td class="td-muted">{{ $student->stop?->name ?? '—' }}</td>
                            <td>
                                @if($student->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Recent trips (driver role) --}}
        @if($user->isDriver() && $user->driver?->trips->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-clock-history"></i> Recent Trips</div>
                <span style="font-size:.75rem;color:var(--muted);">Last 5</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Route</th><th>Date</th><th>Type</th><th>Duration</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach($user->driver->trips as $trip)
                        <tr>
                            <td style="font-weight:600;">{{ $trip->route->name }}</td>
                            <td class="td-muted">{{ $trip->scheduled_at?->format('d M Y H:i') ?? '—' }}</td>
                            <td class="td-muted">{{ ucfirst($trip->type) }}</td>
                            <td class="td-muted">{{ $trip->duration ?? '—' }}</td>
                            <td><span class="badge badge-{{ $trip->status_badge['color'] }}">{{ $trip->status_badge['label'] }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Recent notifications --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-bell-fill"></i> Recent Notifications</div>
                <span style="font-size:.75rem;color:var(--muted);">Last 10</span>
            </div>
            @if($user->notifications->isEmpty())
                <div class="empty-state" style="padding:28px 20px;">
                    <i class="bi bi-bell-slash"></i>
                    <h3>No notifications yet</h3>
                </div>
            @else
            @foreach($user->notifications as $n)
            <div style="display:flex;align-items:flex-start;gap:10px;padding:12px 18px;border-bottom:1px solid rgba(255,255,255,.04);">
                <div style="width:32px;height:32px;border-radius:50%;background:rgba(0,229,195,.08);display:flex;align-items:center;justify-content:center;font-size:.8rem;color:var(--teal);flex-shrink:0;">
                    <i class="bi {{ $n->icon }}"></i>
                </div>
                <div style="flex:1;">
                    <div style="font-size:.84rem;font-weight:{{ $n->is_read ? '400' : '600' }};color:{{ $n->is_read ? 'var(--muted)' : 'var(--white)' }};">{{ $n->title }}</div>
                    <div style="font-size:.72rem;color:var(--muted);">{{ $n->time_ago }}</div>
                </div>
                @if(!$n->is_read)
                    <span style="width:7px;height:7px;border-radius:50%;background:var(--teal);margin-top:4px;flex-shrink:0;"></span>
                @endif
            </div>
            @endforeach
            @endif
        </div>

    </div>
</div>

@endsection