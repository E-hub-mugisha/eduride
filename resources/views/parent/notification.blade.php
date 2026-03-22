@extends('layouts.parent')
@section('title', 'Notifications')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <div class="page-title">Notifications</div>
        <div class="page-sub">All transport alerts for your children.</div>
    </div>
    @if($notifications->total() > 0)
    <form method="POST" action="{{ route('parent.notifications.readAll') }}">
        @csrf
        <button type="submit" class="btn btn-secondary btn-sm">
            <i class="bi bi-check-all"></i> Mark all read
        </button>
    </form>
    @endif
</div>

@if($notifications->isEmpty())
<div class="card">
    <div class="card-body">
        <div class="empty">
            <i class="bi bi-bell-slash" style="font-size:2.2rem;"></i>
            <h3 style="font-family:var(--fd);font-size:1rem;color:var(--muted);margin-bottom:6px;">No notifications yet</h3>
            <p>Transport alerts will appear here as trips happen.</p>
        </div>
    </div>
</div>

@else

{{-- Group by date --}}
@php
    $grouped = $notifications->getCollection()->groupBy(fn($n) => $n->created_at->isToday()
        ? 'Today'
        : ($n->created_at->isYesterday() ? 'Yesterday' : $n->created_at->format('d M Y'))
    );
@endphp

@foreach($grouped as $dateLabel => $items)
<div style="font-size:.7rem;font-weight:700;letter-spacing:.09em;text-transform:uppercase;color:var(--muted);margin:16px 0 8px;padding-left:4px;">
    {{ $dateLabel }}
</div>

<div class="card" style="margin-bottom:8px;">
    @foreach($items as $n)
    <div style="display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-bottom:1px solid rgba(255,255,255,.04);{{ !$n->is_read ? 'background:rgba(0,229,195,.025);' : '' }}"
         id="notif-{{ $n->id }}">

        {{-- Icon --}}
        <div style="width:38px;height:38px;border-radius:50%;
            background:rgba(0,229,195,.08);border:1px solid rgba(0,229,195,.12);
            display:flex;align-items:center;justify-content:center;
            color:var(--teal);font-size:.9rem;flex-shrink:0;margin-top:1px;">
            <i class="bi {{ $n->icon }}"></i>
        </div>

        {{-- Content --}}
        <div style="flex:1;min-width:0;">
            <div style="font-size:.88rem;font-weight:{{ $n->is_read ? '500' : '700' }};color:{{ $n->is_read ? 'var(--muted)' : 'var(--white)' }};margin-bottom:4px;">
                {{ $n->title }}
            </div>
            <div style="font-size:.78rem;color:var(--muted);line-height:1.5;margin-bottom:6px;">
                {{ $n->message }}
            </div>

            {{-- Meta chips --}}
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                <span style="font-size:.68rem;color:var(--muted);">{{ $n->time_ago }}</span>
                @if($n->trip)
                    <span class="badge badge-secondary">{{ $n->trip->route->name }}</span>
                @endif
                @if(isset($n->meta['stop_name']))
                    <span class="badge badge-teal"><i class="bi bi-geo-alt-fill" style="font-size:.6rem;"></i>{{ $n->meta['stop_name'] }}</span>
                @endif
                @if(isset($n->meta['eta_minutes']))
                    <span class="badge badge-info">~{{ $n->meta['eta_minutes'] }} min away</span>
                @endif
            </div>
        </div>

        {{-- Unread indicator --}}
        @if(!$n->is_read)
            <div style="width:8px;height:8px;border-radius:50%;background:var(--teal);flex-shrink:0;margin-top:6px;"></div>
        @endif
    </div>
    @endforeach
</div>
@endforeach

{{-- Pagination --}}
<div style="display:flex;gap:6px;justify-content:center;margin-top:16px;flex-wrap:wrap;">
    @if($notifications->onFirstPage())
        <span style="display:inline-flex;width:36px;height:36px;border-radius:8px;background:rgba(255,255,255,.04);align-items:center;justify-content:center;opacity:.35;"><i class="bi bi-chevron-left" style="font-size:.75rem;"></i></span>
    @else
        <a href="{{ $notifications->previousPageUrl() }}" style="display:inline-flex;width:36px;height:36px;border-radius:8px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);align-items:center;justify-content:center;color:var(--muted);text-decoration:none;transition:all .2s;">
            <i class="bi bi-chevron-left" style="font-size:.75rem;"></i>
        </a>
    @endif

    <span style="display:inline-flex;align-items:center;padding:0 14px;font-size:.82rem;color:var(--muted);">
        Page {{ $notifications->currentPage() }} of {{ $notifications->lastPage() }}
    </span>

    @if($notifications->hasMorePages())
        <a href="{{ $notifications->nextPageUrl() }}" style="display:inline-flex;width:36px;height:36px;border-radius:8px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);align-items:center;justify-content:center;color:var(--muted);text-decoration:none;transition:all .2s;">
            <i class="bi bi-chevron-right" style="font-size:.75rem;"></i>
        </a>
    @else
        <span style="display:inline-flex;width:36px;height:36px;border-radius:8px;background:rgba(255,255,255,.04);align-items:center;justify-content:center;opacity:.35;"><i class="bi bi-chevron-right" style="font-size:.75rem;"></i></span>
    @endif
</div>

@endif

@endsection