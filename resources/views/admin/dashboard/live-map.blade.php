@extends('layouts.admin')
@section('page-title', 'Live Map')
@section('breadcrumb', 'Live Map')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
    .page-content { padding: 0 !important; max-width: 100% !important; }
    .map-layout { display: grid; grid-template-columns: 340px 1fr; height: calc(100vh - 64px); }

    .map-panel {
        background: var(--navy-2);
        border-right: 1px solid rgba(255,255,255,.06);
        display: flex; flex-direction: column; overflow: hidden;
    }
    .map-panel-header {
        padding: 18px 20px; border-bottom: 1px solid rgba(255,255,255,.05);
        display: flex; align-items: center; justify-content: space-between;
    }
    .map-panel-title {
        font-family: var(--font-d); font-size: 1rem; font-weight: 700;
        color: var(--white); display: flex; align-items: center; gap: 8px;
    }
    .map-panel-body { flex: 1; overflow-y: auto; padding: 12px; }

    .trip-row {
        display: flex; align-items: center; gap: 12px;
        padding: 12px; border-radius: 12px; cursor: pointer;
        transition: background .2s; border: 1px solid transparent; margin-bottom: 6px;
    }
    .trip-row:hover   { background: rgba(255,255,255,.04); }
    .trip-row.selected { background: rgba(0,229,195,.07); border-color: rgba(0,229,195,.2); }

    .trip-bus-icon {
        width: 40px; height: 40px; border-radius: 50%;
        background: rgba(0,229,195,.1); border: 1px solid rgba(0,229,195,.2);
        display: flex; align-items: center; justify-content: center;
        color: var(--teal); font-size: .9rem; flex-shrink: 0;
    }
    .trip-bus-icon.stale { background: rgba(251,113,133,.1); border-color: rgba(251,113,133,.2); color: var(--danger); }

    .trip-info { flex: 1; min-width: 0; }
    .trip-info-name { font-size: .88rem; font-weight: 600; color: var(--white); margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .trip-info-meta { font-size: .73rem; color: var(--muted); }

    .speed-chip {
        font-size: .68rem; font-weight: 700;
        background: rgba(99,179,237,.1); color: #63B3ED;
        border: 1px solid rgba(99,179,237,.2);
        padding: 3px 8px; border-radius: 20px; white-space: nowrap;
    }

    #liveMap { width: 100%; height: 100%; }
    .leaflet-tile-pane { filter: brightness(.82) saturate(.65); }

    .panel-empty { text-align: center; padding: 48px 20px; }
    .panel-empty i { font-size: 2rem; color: rgba(122,139,170,.25); display: block; margin-bottom: 12px; }
    .panel-empty p { color: var(--muted); font-size: .85rem; }

    @media (max-width: 768px) {
        .map-layout { grid-template-columns: 1fr; grid-template-rows: 45vh 1fr; }
        #liveMap { order: -1; }
    }
</style>
@endpush

@section('content')
<div class="map-layout">

    {{-- Left panel --}}
    <div class="map-panel">
        <div class="map-panel-header">
            <div class="map-panel-title">
                @if($activeTrips->count()) <span class="live-dot"></span> @endif
                Active Buses
                <span style="font-size:.75rem;font-weight:400;color:var(--muted);">({{ $activeTrips->count() }})</span>
            </div>
            <button onclick="refreshAll()" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:.95rem;" title="Refresh">
                <i class="bi bi-arrow-clockwise" id="refreshIcon"></i>
            </button>
        </div>

        <div class="map-panel-body" id="tripList">
            @if($activeTrips->isEmpty())
                <div class="panel-empty">
                    <i class="bi bi-geo-alt"></i>
                    <p>No buses are active right now.</p>
                </div>
            @else
                @foreach($activeTrips as $trip)
                <div class="trip-row" id="row-{{ $trip->id }}" onclick="focusBus({{ $trip->id }})">
                    <div class="trip-bus-icon {{ $trip->is_location_stale ? 'stale' : '' }}" id="icon-{{ $trip->id }}">
                        <i class="bi bi-bus-front-fill"></i>
                    </div>
                    <div class="trip-info">
                        <div class="trip-info-name">{{ $trip->route->name }}</div>
                        <div class="trip-info-meta">{{ $trip->driver->name }} · {{ $trip->vehicle->plate_number }}</div>
                    </div>
                    <div>
                        <div class="speed-chip" id="speed-{{ $trip->id }}">{{ $trip->current_speed ? round($trip->current_speed) : 0 }} km/h</div>
                        <div style="font-size:.65rem;color:var(--muted);text-align:center;margin-top:3px;" id="updated-{{ $trip->id }}">
                            {{ $trip->location_updated_at?->diffForHumans() ?? '—' }}
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Map --}}
    <div id="liveMap"></div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Data pre-built in the controller — no closures needed here
const INITIAL_TRIPS = @json($tripsData);
const ROUTES_ONLY   = @json($routesData);

// ── Map ───────────────────────────────────────────────────────────────────────
const map = L.map('liveMap', { zoomControl: true, attributionControl: false })
             .setView([-1.9706, 30.1050], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

// ── State ─────────────────────────────────────────────────────────────────────
const busMarkers = {};
let selectedTripId = null;

function makeBusIcon(stale) {
    const color  = stale ? '#FB7185' : '#00E5C3';
    const shadow = stale ? 'rgba(251,113,133,.2)' : 'rgba(0,229,195,.2)';
    return L.divIcon({
        className: '',
        html: '<div style="width:40px;height:40px;border-radius:50%;background:' + color + ';display:flex;align-items:center;justify-content:center;color:#050B18;font-size:1.1rem;box-shadow:0 0 0 8px ' + shadow + ';cursor:pointer;">🚌</div>',
        iconSize: [40, 40],
        iconAnchor: [20, 20],
    });
}

// ── Stop markers for all routes ───────────────────────────────────────────────
ROUTES_ONLY.forEach(function(route) {
    route.stops.forEach(function(s, i) {
        var isLast = (i === route.stops.length - 1);
        var icon = L.divIcon({
            className: '',
            html: '<div style="width:22px;height:22px;border-radius:50%;background:' + (isLast ? '#00E5C3' : '#0D1628') + ';border:2px solid ' + (isLast ? '#00E5C3' : 'rgba(0,229,195,.35)') + ';display:flex;align-items:center;justify-content:center;color:' + (isLast ? '#050B18' : '#00E5C3') + ';font-size:.55rem;font-weight:700;">' + (isLast ? '🏫' : (i + 1)) + '</div>',
            iconSize: [22, 22],
            iconAnchor: [11, 11],
        });
        L.marker([s.lat, s.lng], { icon: icon })
         .bindTooltip(s.name)
         .addTo(map);
    });
});

// ── Initial bus markers ───────────────────────────────────────────────────────
INITIAL_TRIPS.forEach(function(trip) {
    if (!trip.lat || !trip.lng) return;

    var marker = L.marker([trip.lat, trip.lng], { icon: makeBusIcon(trip.stale) })
        .addTo(map)
        .bindPopup('<b>' + trip.name + '</b><br>' + trip.driver + ' · ' + trip.plate + '<br><a href="' + trip.track_url + '">Open detailed track →</a>');

    marker.on('click', function() { focusBus(trip.id); });
    busMarkers[trip.id] = marker;
});

// Fit bounds
var allMarkers = Object.values(busMarkers);
if (allMarkers.length) {
    map.fitBounds(L.featureGroup(allMarkers).getBounds().pad(.3));
}

// ── Focus bus ─────────────────────────────────────────────────────────────────
function focusBus(tripId) {
    selectedTripId = tripId;
    document.querySelectorAll('.trip-row').forEach(function(r) { r.classList.remove('selected'); });
    var row = document.getElementById('row-' + tripId);
    if (row) row.classList.add('selected');
    var m = busMarkers[tripId];
    if (m) { map.setView(m.getLatLng(), 16); m.openPopup(); }
}

// ── Poll every 5 s ────────────────────────────────────────────────────────────
async function pollPositions() {
    for (var i = 0; i < INITIAL_TRIPS.length; i++) {
        var trip = INITIAL_TRIPS[i];
        try {
            var res  = await fetch('/admin/trips/' + trip.id + '/position', { headers: { 'Accept': 'application/json' } });
            var data = await res.json();
            if (!data.lat || !data.lng) continue;

            if (busMarkers[trip.id]) {
                busMarkers[trip.id].setLatLng([data.lat, data.lng]);
                busMarkers[trip.id].setIcon(makeBusIcon(data.is_stale));
            }

            var speedEl   = document.getElementById('speed-'   + trip.id);
            var updatedEl = document.getElementById('updated-' + trip.id);
            var iconEl    = document.getElementById('icon-'    + trip.id);

            if (speedEl)   speedEl.textContent   = (data.speed ? Math.round(data.speed) : 0) + ' km/h';
            if (updatedEl) updatedEl.textContent  = 'Just now';
            if (iconEl)    iconEl.classList.toggle('stale', !!data.is_stale);

            if (selectedTripId === trip.id && busMarkers[trip.id]) {
                map.panTo([data.lat, data.lng]);
            }

            if (data.status === 'completed' || data.status === 'cancelled') {
                var row = document.getElementById('row-' + trip.id);
                if (row) { row.style.opacity = '.4'; row.style.pointerEvents = 'none'; }
            }
        } catch (e) { /* network blip — skip */ }
    }
}

setInterval(pollPositions, 5000);

function refreshAll() {
    var icon = document.getElementById('refreshIcon');
    icon.style.animation = 'spin .6s linear';
    setTimeout(function() { icon.style.animation = ''; }, 700);
    pollPositions();
}

var style = document.createElement('style');
style.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
document.head.appendChild(style);
</script>
@endpush