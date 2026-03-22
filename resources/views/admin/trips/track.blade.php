@extends('layouts.admin')
@section('page-title', 'Track Trip')
@section('breadcrumb', 'Trips / Track')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
    .page-content { padding: 0 !important; max-width: 100% !important; }
    .track-layout { display: grid; grid-template-columns: 320px 1fr; height: calc(100vh - 64px); }
    #trackMap { width: 100%; height: 100%; }
    .leaflet-tile-pane { filter: brightness(.82) saturate(.65); }

    .info-panel {
        background: var(--navy-2);
        border-right: 1px solid rgba(255,255,255,.06);
        display: flex; flex-direction: column;
        overflow-y: auto;
    }
    .info-section {
        padding: 18px 20px;
        border-bottom: 1px solid rgba(255,255,255,.05);
    }
    .info-section-label {
        font-size: .68rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
        color: var(--muted); margin-bottom: 12px;
        display: flex; align-items: center; gap: 6px;
    }
    .info-section-label i { color: var(--teal); }
    .meta-row { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,.04); }
    .meta-row:last-child { border-bottom: none; }
    .meta-key { font-size: .8rem; color: var(--muted); }
    .meta-val { font-size: .85rem; color: var(--white); font-weight: 500; text-align: right; }

    .stop-item { display: flex; align-items: flex-start; gap: 10px; padding: 8px 0; }
    .stop-bullet { width: 18px; height: 18px; border-radius: 50%; background: var(--navy-3); border: 2px solid rgba(0,229,195,.3); display: flex; align-items: center; justify-content: center; font-size: .55rem; font-weight: 700; color: var(--muted); flex-shrink: 0; margin-top: 1px; }
    .stop-bullet.school { background: var(--teal); border-color: var(--teal); color: var(--navy); }
    .stop-item-name { font-size: .83rem; color: var(--white); font-weight: 500; }
    .stop-item-sub  { font-size: .72rem; color: var(--muted); }

    .live-status {
        padding: 14px 20px;
        background: rgba(0,229,195,.05);
        border-bottom: 1px solid rgba(0,229,195,.1);
        display: flex; align-items: center; gap: 12px;
    }
    .live-icon { width: 36px; height: 36px; border-radius: 10px; background: rgba(0,229,195,.12); display: flex; align-items: center; justify-content: center; color: var(--teal); font-size: .9rem; flex-shrink: 0; }
    .live-text-title { font-size: .88rem; font-weight: 600; color: var(--white); display: flex; align-items: center; gap: 6px; }
    .live-text-sub { font-size: .73rem; color: var(--muted); }

    @media(max-width:768px) {
        .track-layout { grid-template-columns: 1fr; grid-template-rows: 45vh 1fr; }
        #trackMap { order: -1; }
    }
</style>
@endpush

@section('content')
<div class="track-layout">

    {{-- Info Panel --}}
    <div class="info-panel">

        {{-- Live status --}}
        @if($trip->isInProgress())
        <div class="live-status">
            <div class="live-icon"><i class="bi bi-geo-alt-fill"></i></div>
            <div>
                <div class="live-text-title">
                    <span class="live-dot"></span> Live — Auto-refreshing
                </div>
                <div class="live-text-sub">Map updates every 5 seconds</div>
            </div>
        </div>
        @endif

        {{-- Trip meta --}}
        <div class="info-section">
            <div class="info-section-label"><i class="bi bi-info-circle-fill"></i> Trip Info</div>
            <div class="meta-row">
                <span class="meta-key">Route</span>
                <span class="meta-val">{{ $trip->route->name }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-key">Status</span>
                <span class="meta-val">
                    <span class="badge badge-{{ $trip->status_badge['color'] }}">{{ $trip->status_badge['label'] }}</span>
                </span>
            </div>
            <div class="meta-row">
                <span class="meta-key">Type</span>
                <span class="meta-val">{{ ucfirst($trip->type) }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-key">Started</span>
                <span class="meta-val">{{ $trip->started_at?->format('H:i') ?? '—' }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-key">Ended</span>
                <span class="meta-val">{{ $trip->ended_at?->format('H:i') ?? '—' }}</span>
            </div>
            @if($trip->isCompleted())
            <div class="meta-row">
                <span class="meta-key">Duration</span>
                <span class="meta-val" style="color:var(--teal);">{{ $trip->duration }}</span>
            </div>
            @endif
        </div>

        {{-- Driver & vehicle --}}
        <div class="info-section">
            <div class="info-section-label"><i class="bi bi-person-badge-fill"></i> Driver & Vehicle</div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                <img src="{{ $trip->driver->avatar_url }}" style="width:38px;height:38px;border-radius:50%;object-fit:cover;" alt="">
                <div>
                    <div style="font-size:.88rem;font-weight:600;color:var(--white);">{{ $trip->driver->name }}</div>
                    <div style="font-size:.73rem;color:var(--muted);">{{ $trip->driver->phone }}</div>
                </div>
            </div>
            <div class="meta-row">
                <span class="meta-key">Vehicle</span>
                <span class="meta-val">{{ $trip->vehicle->brand }} {{ $trip->vehicle->model }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-key">Plate</span>
                <span class="meta-val" style="font-family:monospace;letter-spacing:.05em;">{{ $trip->vehicle->plate_number }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-key">Capacity</span>
                <span class="meta-val">{{ $trip->vehicle->capacity }} seats</span>
            </div>
        </div>

        {{-- Live position --}}
        @if($trip->isInProgress())
        <div class="info-section">
            <div class="info-section-label"><i class="bi bi-broadcast"></i> Live Position</div>
            <div class="meta-row">
                <span class="meta-key">Speed</span>
                <span class="meta-val" id="liveSpeed" style="color:var(--teal);">{{ $trip->current_speed ? round($trip->current_speed) : 0 }} km/h</span>
            </div>
            <div class="meta-row">
                <span class="meta-key">Last ping</span>
                <span class="meta-val" id="livePing">{{ $trip->location_updated_at?->diffForHumans() ?? '—' }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-key">GPS signal</span>
                <span class="meta-val" id="liveSignal">
                    @if($trip->is_location_stale)
                        <span style="color:var(--danger);">Stale</span>
                    @else
                        <span style="color:var(--success);">Good</span>
                    @endif
                </span>
            </div>
        </div>
        @endif

        {{-- Stops --}}
        <div class="info-section">
            <div class="info-section-label"><i class="bi bi-geo-alt-fill"></i> Stops ({{ count($stops) }})</div>
            @foreach($stops as $stop)
            <div class="stop-item">
                <div class="stop-bullet {{ $loop->last ? 'school' : '' }}">
                    @if($loop->last) <i class="bi bi-building" style="font-size:.6rem;"></i>
                    @else {{ $stop['order'] }}
                    @endif
                </div>
                <div>
                    <div class="stop-item-name">{{ $stop['name'] }}</div>
                </div>
            </div>
            @endforeach
        </div>

    </div>

    {{-- Map --}}
    <div id="trackMap"></div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const TRIP_ID   = {{ $trip->id }};
const IS_LIVE   = {{ $trip->isInProgress() ? 'true' : 'false' }};
const STOPS     = @json($stops);
const POLYLINE  = @json($polyline);      // historic path from DB
const CSRF      = document.querySelector('meta[name=csrf-token]')?.content;

// ── Map ───────────────────────────────────────────────────────────────────────
const map = L.map('trackMap', { zoomControl: true, attributionControl: false })
             .setView([-1.9706, 30.1050], 14);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

// ── Historic route path ───────────────────────────────────────────────────────
const historyLine = L.polyline(POLYLINE, { color: '#00E5C3', weight: 3, opacity: .6, dashArray: IS_LIVE ? null : '6 4' }).addTo(map);

// ── Live path (extends during active trip) ────────────────────────────────────
const liveLine = L.polyline([], { color: '#FFB547', weight: 2.5, opacity: .8 }).addTo(map);
const livePath = [...POLYLINE];

// ── Stop markers ──────────────────────────────────────────────────────────────
STOPS.forEach((s, i) => {
    const isSchool = i === STOPS.length - 1;
    const icon = L.divIcon({
        className: '',
        html: `<div style="width:26px;height:26px;border-radius:50%;background:${isSchool ? '#00E5C3' : '#0D1628'};border:2px solid ${isSchool ? '#00E5C3' : 'rgba(0,229,195,.4)'};display:flex;align-items:center;justify-content:center;color:${isSchool ? '#050B18' : '#00E5C3'};font-size:.6rem;font-weight:700;">${isSchool ? '🏫' : s.order}</div>`,
        iconSize: [26, 26], iconAnchor: [13, 13],
    });
    L.marker([s.lat, s.lng], { icon })
     .bindTooltip(s.name, { permanent: false, direction: 'top' })
     .addTo(map);
});

// ── Bus marker ────────────────────────────────────────────────────────────────
const busIcon = L.divIcon({
    className: '',
    html: `<div style="width:44px;height:44px;border-radius:50%;background:#00E5C3;display:flex;align-items:center;justify-content:center;color:#050B18;font-size:1.1rem;box-shadow:0 0 0 8px rgba(0,229,195,.15);">🚌</div>`,
    iconSize: [44, 44], iconAnchor: [22, 22],
});

let busMarker = null;
@if($trip->current_latitude && $trip->current_longitude)
busMarker = L.marker([{{ $trip->current_latitude }}, {{ $trip->current_longitude }}], { icon: busIcon }).addTo(map);
@endif

// Fit map to all content
const allLatLngs = [...POLYLINE, ...STOPS.map(s => [s.lat, s.lng])];
if (allLatLngs.length) map.fitBounds(L.latLngBounds(allLatLngs).pad(.15));

// ── Live polling (only for in-progress trips) ─────────────────────────────────
if (IS_LIVE) {
    setInterval(async () => {
        try {
            const res  = await fetch(`/admin/trips/${TRIP_ID}/position`, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();

            if (!data.lat || !data.lng) return;

            // Move / create bus marker
            if (!busMarker) {
                busMarker = L.marker([data.lat, data.lng], { icon: busIcon }).addTo(map);
            } else {
                busMarker.setLatLng([data.lat, data.lng]);
            }

            // Extend live path
            livePath.push([data.lat, data.lng]);
            liveLine.setLatLngs(livePath);

            // Update panel
            document.getElementById('liveSpeed').textContent  = (data.speed ? Math.round(data.speed) : 0) + ' km/h';
            document.getElementById('livePing').textContent   = 'Just now';
            document.getElementById('liveSignal').innerHTML   = data.is_stale
                ? '<span style="color:var(--danger);">Stale</span>'
                : '<span style="color:var(--success);">Good</span>';

            // If trip ended
            if (data.status === 'completed' || data.status === 'cancelled') {
                document.querySelector('.live-status')?.remove();
            }
        } catch (_) { /* skip */ }
    }, 5000);
}
</script>
@endpush