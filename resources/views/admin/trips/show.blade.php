@extends('layouts.admin')
@section('page-title', 'Trip #' . $trip->id)
@section('breadcrumb', 'Trips / #' . $trip->id)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
    #tripMap { height: 340px; border-radius: 14px; overflow: hidden; border: 1px solid rgba(255,255,255,.07); }
    .leaflet-tile-pane { filter: brightness(.82) saturate(.65); }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1>Trip — {{ $trip->route->name }}</h1>
        <p>{{ $trip->scheduled_at?->format('d M Y, H:i') ?? 'Unscheduled' }}</p>
    </div>
    <div class="page-header-actions">
        @if($trip->isInProgress())
        <a href="{{ route('admin.trips.track', $trip) }}" class="btn btn-primary">
            <i class="bi bi-geo-alt-fill"></i> Live Track
        </a>
        @endif
        <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">

    {{-- Trip summary --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-info-circle-fill"></i> Summary</div>
            <span class="badge badge-{{ $trip->status_badge['color'] }}">
                @if($trip->isInProgress()) <span class="live-dot"></span> @endif
                {{ $trip->status_badge['label'] }}
            </span>
        </div>
        <div class="card-body" style="padding:0;">
            @foreach([
                ['Route',         $trip->route->name],
                ['Type',          ucfirst($trip->type)],
                ['Scheduled',     $trip->scheduled_at?->format('d M Y H:i') ?? '—'],
                ['Started',       $trip->started_at?->format('H:i') ?? '—'],
                ['Ended',         $trip->ended_at?->format('H:i') ?? '—'],
                ['Duration',      $trip->duration ?? '—'],
                ['Delay',         $trip->delay_minutes > 0 ? '+' . $trip->delay_minutes . ' min' : 'None'],
                ['GPS Pings',     $trip->locations->count()],
            ] as [$label, $value])
            <div style="display:flex;justify-content:space-between;align-items:center;padding:11px 20px;border-bottom:1px solid rgba(255,255,255,.04);">
                <span style="font-size:.82rem;color:var(--muted);">{{ $label }}</span>
                <span style="font-size:.88rem;font-weight:500;color:var(--white);">{{ $value }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Driver & vehicle --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-person-badge-fill"></i> Driver & Vehicle</div>
        </div>
        <div class="card-body">
            <div class="avatar-row" style="margin-bottom:16px;">
                <img src="{{ $trip->driver->avatar_url }}" style="width:46px;height:46px;border-radius:50%;object-fit:cover;" alt="">
                <div>
                    <div style="font-size:.95rem;font-weight:600;color:var(--white);">{{ $trip->driver->name }}</div>
                    <div style="font-size:.78rem;color:var(--muted);">{{ $trip->driver->phone }}</div>
                </div>
                <a href="{{ route('admin.drivers.show', $trip->driver) }}" class="btn btn-secondary btn-sm" style="margin-left:auto;">
                    <i class="bi bi-eye"></i>
                </a>
            </div>
            @foreach([
                ['Vehicle',   $trip->vehicle->brand . ' ' . $trip->vehicle->model],
                ['Plate',     $trip->vehicle->plate_number],
                ['Capacity',  $trip->vehicle->capacity . ' seats'],
            ] as [$label, $value])
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.04);">
                <span style="font-size:.82rem;color:var(--muted);">{{ $label }}</span>
                <span style="font-size:.88rem;font-weight:500;color:var(--white);">{{ $value }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Map --}}
    @if($polyline->isNotEmpty() || ($trip->current_latitude && $trip->current_longitude))
    <div class="card" style="grid-column:1/-1;">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-map-fill"></i> Route Taken</div>
            @if($trip->isInProgress())
                <a href="{{ route('admin.trips.track', $trip) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-fullscreen"></i> Full Live View
                </a>
            @endif
        </div>
        <div class="card-body">
            <div id="tripMap"></div>
        </div>
    </div>
    @endif

    {{-- Notifications log --}}
    @if($trip->notifications->isNotEmpty())
    <div class="card" style="grid-column:1/-1;">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-bell-fill"></i> Notifications Sent ({{ $trip->notifications->count() }})</div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Type</th><th>Title</th><th>Recipient</th><th>Time</th></tr>
                </thead>
                <tbody>
                    @foreach($trip->notifications as $n)
                    <tr>
                        <td><span class="badge badge-{{ $n->color }}"><i class="bi {{ $n->icon }}" style="font-size:.65rem;"></i></span></td>
                        <td style="font-weight:500;">{{ $n->title }}</td>
                        <td class="td-muted">{{ $n->user->name }}</td>
                        <td class="td-muted">{{ $n->created_at->format('H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
{{-- Pre-build stops array in PHP — never use closures inside @json() in Blade --}}
@php
    $stopsData = [];
    foreach ($trip->route->stops as $s) {
        $stopsData[] = [
            'lat'   => (float) $s->latitude,
            'lng'   => (float) $s->longitude,
            'name'  => $s->name,
            'order' => $s->order,
        ];
    }
@endphp
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
@if($polyline->isNotEmpty() || ($trip->current_latitude && $trip->current_longitude))
var polylineData = @json($polyline);
var stops = @json($stopsData);

var map = L.map('tripMap', { zoomControl: true, attributionControl: false })
           .setView([-1.9706, 30.1050], 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

if (polylineData.length) {
    var line = L.polyline(polylineData, { color: '#00E5C3', weight: 3, opacity: .7 }).addTo(map);
    map.fitBounds(line.getBounds().pad(.15));
}

stops.forEach(function(s, i) {
    var isLast = i === stops.length - 1;
    var icon = L.divIcon({
        className: '',
        html: '<div style="width:22px;height:22px;border-radius:50%;background:' + (isLast ? '#00E5C3' : '#0D1628') + ';border:2px solid ' + (isLast ? '#00E5C3' : 'rgba(0,229,195,.4)') + ';display:flex;align-items:center;justify-content:center;color:' + (isLast ? '#050B18' : '#00E5C3') + ';font-size:.55rem;font-weight:700;">' + (isLast ? '🏫' : s.order) + '</div>',
        iconSize: [22, 22], iconAnchor: [11, 11],
    });
    L.marker([s.lat, s.lng], { icon: icon }).bindTooltip(s.name).addTo(map);
});

@if($trip->current_latitude && $trip->current_longitude)
var busIcon = L.divIcon({
    className: '',
    html: '<div style="width:38px;height:38px;border-radius:50%;background:#00E5C3;display:flex;align-items:center;justify-content:center;color:#050B18;font-size:.95rem;box-shadow:0 0 0 6px rgba(0,229,195,.15);">🚌</div>',
    iconSize: [38, 38], iconAnchor: [19, 19],
});
L.marker([{{ $trip->current_latitude }}, {{ $trip->current_longitude }}], { icon: busIcon }).addTo(map);
@endif
@endif
</script>
@endpush