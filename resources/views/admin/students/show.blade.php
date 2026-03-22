@extends('layouts.admin')
@section('page-title', $student->full_name)
@section('breadcrumb', 'Students / ' . $student->full_name)

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>{{ $student->full_name }}</h1>
        <p>{{ $student->display_grade }} · {{ $student->route?->name ?? 'No route assigned' }}</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

    {{-- Student info --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-person-fill"></i> Student Info</div>
            <span class="badge badge-{{ $student->is_active ? 'success' : 'danger' }}">
                {{ $student->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div class="card-body">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;">
                <img src="{{ $student->photo_url }}" style="width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid rgba(0,229,195,.25);" alt="">
                <div>
                    <div style="font-family:var(--font-d);font-size:1.05rem;font-weight:700;color:var(--white);">{{ $student->full_name }}</div>
                    @if($student->student_id)
                        <div style="font-size:.78rem;color:var(--muted);">ID: {{ $student->student_id }}</div>
                    @endif
                </div>
            </div>
            @foreach([
                ['Grade',         $student->display_grade ?: '—'],
                ['Date of Birth', $student->date_of_birth?->format('d M Y') ?? '—'],
                ['Age',           $student->age ? $student->age . ' years' : '—'],
            ] as [$label, $value])
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.04);">
                <span style="font-size:.82rem;color:var(--muted);">{{ $label }}</span>
                <span style="font-size:.88rem;font-weight:500;color:var(--white);">{{ $value }}</span>
            </div>
            @endforeach
            @if($student->medical_notes)
            <div style="margin-top:14px;padding:12px 14px;background:rgba(251,113,133,.06);border:1px solid rgba(251,113,133,.15);border-radius:10px;">
                <div style="font-size:.72rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--danger);margin-bottom:5px;"><i class="bi bi-heart-pulse-fill"></i> Medical Notes</div>
                <div style="font-size:.83rem;color:var(--white);">{{ $student->medical_notes }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Parent info --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-people-fill"></i> Parent / Guardian</div>
        </div>
        <div class="card-body">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <img src="{{ $student->user->avatar_url }}" style="width:48px;height:48px;border-radius:50%;object-fit:cover;" alt="">
                <div>
                    <div style="font-size:.95rem;font-weight:600;color:var(--white);">{{ $student->user->name }}</div>
                    <div style="font-size:.78rem;color:var(--muted);">{{ $student->user->email }}</div>
                </div>
            </div>
            @foreach([
                ['Phone', $student->user->phone ?? '—'],
            ] as [$label, $value])
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.04);">
                <span style="font-size:.82rem;color:var(--muted);">{{ $label }}</span>
                <span style="font-size:.88rem;font-weight:500;color:var(--white);">{{ $value }}</span>
            </div>
            @endforeach
            @if($student->user->phone)
            <a href="tel:{{ $student->user->phone }}" class="btn btn-secondary btn-sm" style="margin-top:14px;">
                <i class="bi bi-telephone"></i> Call Parent
            </a>
            @endif
        </div>
    </div>

    {{-- Transport assignment --}}
    <div class="card" style="grid-column:1/-1;">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-signpost-2-fill"></i> Transport Assignment</div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0;">
            <div style="padding:20px;border-right:1px solid rgba(255,255,255,.05);">
                <div style="font-size:.73rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:12px;">Route</div>
                @if($student->route)
                    <div style="font-size:1rem;font-weight:700;color:var(--white);margin-bottom:4px;">{{ $student->route->name }}</div>
                    <div style="font-size:.8rem;color:var(--muted);margin-bottom:12px;">{{ $student->route->path_summary }}</div>
                    <a href="{{ route('admin.routes.show', $student->route) }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-eye"></i> View Route
                    </a>
                @else
                    <div style="color:var(--muted);font-size:.88rem;">Not assigned to a route.</div>
                @endif
            </div>
            <div style="padding:20px;">
                <div style="font-size:.73rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:12px;">Boarding Stop</div>
                @if($student->stop)
                    <div style="font-size:1rem;font-weight:700;color:var(--white);margin-bottom:4px;">{{ $student->stop->name }}</div>
                    @if($student->stop->landmark)
                        <div style="font-size:.8rem;color:var(--muted);margin-bottom:8px;">{{ $student->stop->landmark }}</div>
                    @endif
                    <div style="font-size:.78rem;color:var(--muted);font-family:monospace;">
                        {{ $student->stop->latitude }}, {{ $student->stop->longitude }}
                    </div>
                @else
                    <div style="color:var(--muted);font-size:.88rem;">No boarding stop set.</div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection