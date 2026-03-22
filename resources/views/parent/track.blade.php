@extends('layouts.parent')
@section('title', 'Track Bus')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
    body { padding-bottom: 0 !important; }
    .page { padding: 0 !important; max-width: 100% !important; }

    /* Full screen map */
    #trackMap { position: fixed; top: 60px; left: 0; right: 0; bottom: 0; z-index: 0; }
    .leaflet-tile-pane { filter: brightness(.82) saturate(.65); }

    /* Top HUD */
    .track-hud-top {
        position: fixed; top: 60px; left: 0; right: 0; z-index: 10;
        padding: 12px 14px;
        background: linear-gradient(to bottom, rgba(5,11,24,.92), transparent);
        pointer-events: none;
    }
    .track-info-pill {
        background: rgba(13,22,40,.88);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 14px;
        padding: 12px 14px;
        display: flex; align-items: center; gap: 12px;
        pointer-events: all;
    }
    .bus-pulse {
        width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
        background: rgba(0,229,195,.12); border: 1px solid rgba(0,229,195,.25);
        display: flex; align-items: center; justify-content: center;
        color: var(--teal); font-size: .95rem;
        animation: pulse 2s ease-in-out infinite;
    }
    @keyframes pulse { 0%,100%{box-shadow:0 0 0 0 rgba(0,229,195,.25);} 50%{box-shadow:0 0 0 8px rgba(0,229,195,0);} }
    .pill-route { font-family: var(--fd); font-size: .9rem; font-weight: 700; color: var(--white); }
    .pill-meta  { font-size: .73rem; color: var(--muted); margin-top: 2px; }

    /* Bottom panel */
    .track-bottom {
        position: fixed; bottom: 0; left: 0; right: 0; z-index: 10;
        background: rgba(13,22,40,.92);
        backdrop-filter: blur(18px);
        border-top: 1px solid rgba(255,255,255,.07);
        border-radius: 20px 20px 0 0;
        padding: 18px 16px 24px;
    }
    .stats-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; margin-bottom: 14px; }
    .stat-box { background: var(--navy-3); border: 1px solid rgba(255,255,255,.06); border-radius: 12px; padding: 12px; text-align: center; }
    .stat-val { font-family: var(--fd); font-size: 1.15rem; font-weight: 800; color: var(--white); }
    .stat-val.teal { color: var(--teal); }
    .stat-val.gold  { color: var(--gold); }
    .stat-lbl { font-size: .67rem; color: var(--muted); margin-top: 2px; }

    /* Stop ETA card */
    .stop-eta {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 14px;
        background: rgba(255,181,71,.06);
        border: 1px solid rgba(255,181,71,.15);
        border-radius: 14px;
    }
    .stop-eta-icon { width: 36px; height: 36px; border-radius: 50%; background: rgba(255,181,71,.12); display: flex; align-items: center; justify-content: center; color: var(--gold); flex-shrink: 0; }
    .stop-eta-name { font-size: .85rem; font-weight: 600; color: var(--white); }
    .stop-eta-time { font-size: .75rem; color: var(--muted); margin-top: 2px; }
    .stop-eta-val  { margin-left: auto; font-family: var(--fd); font-size: 1rem; font-weight: 800; color: var(--gold); text-align: right; }

    /* Arrived banner */
    .arrived-banner {
        display: none; align-items: center; gap: 10px;
        padding: 12px 14px;
        background: rgba(52,211,153,.08);
        border: 1px solid rgba(52,211,153,.2);
        border-radius: 14px;
        color: var(--success); font-weight: 600; font-size: .88rem;
    }
    .arrived-banner.show { display: flex; }
</style>
@endpush

@section('content')

<div id="trackMap"></div>

<!-- Top HUD -->
<div class="track-hud-top">
    <div class="track-info-pill">
        <div class="bus-pulse"><i class="bi bi-bus-front-fill"></i></div>
        <div style="flex:1;min-width:0;">
            <div class="pill-route">{{ $trip->route->name }}</div>
            <div class="pill-meta">
                <span class="live-dot"></span>
                {{ $trip->driver->name }} · {{ $trip->vehicle->plate_number }}
            </div>
        </div>
        <a href="{{ route('parent.dashboard') }}" style="color:var(--muted);font-size:1rem;flex-shrink:0;">
            <i class="bi bi-x-lg"></i>
        </a>
    </div>
</div>

<!-- Bottom panel -->
<div class="track-bottom">
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-val teal" id="liveSpeed">{{ $trip->current_speed ? round($trip->current_speed) : 0 }}</div>
            <div class="stat-lbl">km/h</div>
        </div>
        <div class="stat-box">
            <div class="stat-val" id="livePing">—</div>
            <div class="stat-lbl">Last update</div>
        </div>
        <div class="stat-box">
            <div class="stat-val gold" id="liveDelay">
                {{ $trip->delay_minutes > 0 ? '+' . $trip->delay_minutes . 'm' : 'On time' }}
            </div>
            <div class="stat-lbl">Punctuality</div>
        </div>
    </div>

    @if($student?->stop)
    <div class="arrived-banner" id="arrivedBanner">
        <i class="bi bi-check-circle-fill"></i>
        Bus has arrived at your stop!
    </div>

    <div class="stop-eta" id="etaCard">
        <div class="stop-eta-icon"><i class="bi bi-geo-alt-fill"></i></div>
        <div>
            <div class="stop-eta-name">{{ $student->stop->name }}</div>
            <div class="stop-eta-time">Your child's boarding stop</div>
        </div>
        <div class="stop-eta-val" id="etaValue">Loading…</div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const TRIP_ID  = {{ $trip->id }};
const STOPS    = @json($stops);
@if($student?->stop)
const MY_STOP  = { lat: {{ (float)$student->stop->latitude }}, lng: {{ (float)$student->stop->longitude }}, name: '{{ $student->stop->name }}' };
@else
const MY_STOP  = null;
@endif

// ── Map ───────────────────────────────────────────────────────────────────────
const map = L.map('trackMap', { zoomControl: false, attributionControl: false })
             .setView([-1.9706, 30.1050], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

// ── Route stops ───────────────────────────────────────────────────────────────
STOPS.forEach((s, i) => {
    const isMine   = s.is_mine;
    const isSchool = i === STOPS.length - 1;
    const color    = isMine ? '#FFB547' : (isSchool ? '#00E5C3' : '#0D1628');
    const border   = isMine ? '#FFB547' : (isSchool ? '#00E5C3' : 'rgba(0,229,195,.35)');
    const textCol  = (isMine || isSchool) ? '#050B18' : '#00E5C3';

    const icon = L.divIcon({
        className: '',
        html: `<div style="
            width:${isMine ? '32px' : '24px'};
            height:${isMine ? '32px' : '24px'};
            border-radius:50%;
            background:${color}; border:2px solid ${border};
            display:flex;align-items:center;justify-content:center;
            color:${textCol};font-size:.6rem;font-weight:700;
            box-shadow:${isMine ? '0 0 0 6px rgba(255,181,71,.2)' : 'none'};
        ">${isSchool ? '🏫' : (isMine ? '⭐' : s.order)}</div>`,
        iconSize: isMine ? [32, 32] : [24, 24],
        iconAnchor: isMine ? [16, 16] : [12, 12],
    });

    const marker = L.marker([s.lat, s.lng], { icon }).addTo(map);
    if (isMine) {
        marker.bindPopup(`<b>Your stop</b><br>${s.name}`, { maxWidth: 160 }).openPopup();
    } else {
        marker.bindTooltip(s.name, { direction: 'top' });
    }
});

// ── Bus marker ────────────────────────────────────────────────────────────────
const busIcon = L.divIcon({
    className: '',
    html: `<div style="width:46px;height:46px;border-radius:50%;background:#00E5C3;display:flex;align-items:center;justify-content:center;color:#050B18;font-size:1.2rem;box-shadow:0 0 0 10px rgba(0,229,195,.15),0 0 0 20px rgba(0,229,195,.05);">🚌</div>`,
    iconSize: [46, 46], iconAnchor: [23, 23],
});

let busMarker = null;
const pathCoords = [];
const polyline   = L.polyline([], { color: '#00E5C3', weight: 3, opacity: .65 }).addTo(map);

@if($trip->current_latitude && $trip->current_longitude)
busMarker = L.marker([{{ $trip->current_latitude }}, {{ $trip->current_longitude }}], { icon: busIcon }).addTo(map);
pathCoords.push([{{ $trip->current_latitude }}, {{ $trip->current_longitude }}]);
polyline.setLatLngs(pathCoords);
map.setView([{{ $trip->current_latitude }}, {{ $trip->current_longitude }}], 15);
@endif

// ── Haversine ─────────────────────────────────────────────────────────────────
function haversine(lat1, lng1, lat2, lng2) {
    const R = 6371000, dLat = (lat2-lat1)*Math.PI/180, dLng = (lng2-lng1)*Math.PI/180;
    const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLng/2)**2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
}

// ── Poll every 5s ─────────────────────────────────────────────────────────────
async function poll() {
    try {
        const res  = await fetch(`/admin/trips/${TRIP_ID}/position`, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        if (!data.lat || !data.lng) return;

        // Move bus
        if (!busMarker) {
            busMarker = L.marker([data.lat, data.lng], { icon: busIcon }).addTo(map);
        } else {
            busMarker.setLatLng([data.lat, data.lng]);
        }

        // Extend path
        pathCoords.push([data.lat, data.lng]);
        polyline.setLatLngs(pathCoords);

        // Smoothly pan to bus
        map.panTo([data.lat, data.lng], { animate: true, duration: 1 });

        // Update stats
        document.getElementById('liveSpeed').textContent = data.speed ? Math.round(data.speed) : 0;
        document.getElementById('livePing').textContent  = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        // ETA to my stop
        if (MY_STOP) {
            const dist = haversine(data.lat, data.lng, MY_STOP.lat, MY_STOP.lng);
            const etaEl    = document.getElementById('etaValue');
            const arrivedEl = document.getElementById('arrivedBanner');
            const etaCard   = document.getElementById('etaCard');

            if (dist < 100) {
                arrivedEl.classList.add('show');
                etaCard.style.display = 'none';
            } else {
                const avgSpeed = data.speed && data.speed > 2 ? data.speed : 15;  // km/h
                const etaSec   = (dist / 1000) / avgSpeed * 3600;
                const etaMin   = Math.max(1, Math.round(etaSec / 60));
                if (etaEl) etaEl.textContent = dist < 500 ? 'Approaching!' : `~${etaMin} min`;
            }
        }

        // If trip ended
        if (data.status === 'completed' || data.status === 'cancelled') {
            clearInterval(pollInterval);
            document.querySelector('.bus-pulse').style.animation = 'none';
            document.querySelector('.live-dot').style.animation  = 'none';
        }
    } catch (_) {}
}

const pollInterval = setInterval(poll, 5000);
poll(); // immediate first call
</script>
@endpush