<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Live Trip · EDURIDE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --navy:#050B18; --navy-2:#0D1628; --navy-3:#121E35;
            --teal:#00E5C3; --teal-dim:#00B89A; --gold:#FFB547;
            --white:#F0F4FF; --muted:#7A8BAA; --danger:#FB7185; --success:#34D399;
            --fd:'Syne',sans-serif; --fb:'DM Sans',sans-serif;
        }
        html, body { height:100%; background:var(--navy); color:var(--white); font-family:var(--fb); }
        #map { position:fixed; inset:0; z-index:0; }
        .leaflet-tile-pane { filter:brightness(.85) saturate(.7); }

        .hud-top {
            position:fixed; top:0; left:0; right:0; z-index:10;
            padding:12px 16px;
            background:linear-gradient(to bottom,rgba(5,11,24,.95),transparent);
            display:flex; align-items:center; gap:12px;
        }
        .back-btn {
            width:38px; height:38px; border-radius:10px;
            background:rgba(13,22,40,.8); border:1px solid rgba(255,255,255,.1);
            display:flex; align-items:center; justify-content:center;
            color:var(--white); text-decoration:none; font-size:1rem; flex-shrink:0;
        }
        .hud-route {
            flex:1; background:rgba(13,22,40,.85); backdrop-filter:blur(12px);
            border:1px solid rgba(255,255,255,.08); border-radius:14px; padding:10px 14px;
        }
        .hud-route-name { font-family:var(--fd); font-size:.95rem; font-weight:700; color:var(--white); }
        .hud-route-meta { font-size:.75rem; color:var(--muted); display:flex; align-items:center; gap:8px; margin-top:2px; }
        .live-dot { display:inline-block; width:7px; height:7px; border-radius:50%; background:var(--success); animation:blink 1.4s infinite; }
        @@keyframes blink { 0%,100%{opacity:1;} 50%{opacity:.2;} }
        .chip {
            display:inline-flex; align-items:center; gap:5px;
            background:rgba(0,229,195,.1); border:1px solid rgba(0,229,195,.2);
            color:var(--teal); font-size:.7rem; font-weight:700;
            padding:3px 9px; border-radius:20px; text-transform:uppercase; letter-spacing:.05em;
        }

        .hud-bottom {
            position:fixed; bottom:0; left:0; right:0; z-index:10;
            background:rgba(13,22,40,.92); backdrop-filter:blur(16px);
            border-top:1px solid rgba(255,255,255,.07);
            border-radius:20px 20px 0 0; padding:20px 16px 28px;
        }
        .stats-row { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:16px; }
        .stat-box { background:var(--navy-3); border:1px solid rgba(255,255,255,.07); border-radius:12px; padding:12px; text-align:center; }
        .stat-val { font-family:var(--fd); font-size:1.2rem; font-weight:800; color:var(--white); }
        .stat-val.teal { color:var(--teal); }
        .stat-lbl { font-size:.68rem; color:var(--muted); margin-top:2px; }

        .btn-row { display:flex; gap:10px; }
        .btn {
            display:flex; align-items:center; justify-content:center; gap:7px;
            font-family:var(--fd); font-size:.88rem; font-weight:700;
            padding:13px; border-radius:12px; border:none; cursor:pointer;
            transition:all .25s; flex:1;
        }
        .btn-end { background:rgba(251,113,133,.12); border:1px solid rgba(251,113,133,.25); color:var(--danger); }
        .btn-end:hover { background:rgba(251,113,133,.22); }
        .btn-sos { background:var(--danger); color:#fff; flex:0; padding:13px 18px; border-radius:12px; border:none; cursor:pointer; font-size:1rem; }
        .btn:disabled { opacity:.5; cursor:not-allowed; }

        .gps-banner {
            position:fixed; top:80px; left:50%; transform:translateX(-50%);
            background:rgba(251,113,133,.15); border:1px solid rgba(251,113,133,.3);
            color:var(--danger); font-size:.82rem; font-weight:500;
            padding:9px 18px; border-radius:10px; z-index:20;
            display:none; align-items:center; gap:7px;
        }
        .gps-banner.show { display:flex; }

        .notif {
            position:fixed; top:80px; right:16px;
            background:var(--navy-2); border:1px solid rgba(0,229,195,.2);
            border-radius:14px; padding:14px 16px; max-width:260px; z-index:30;
            box-shadow:0 10px 30px rgba(0,0,0,.4);
            transform:translateX(110%); transition:transform .35s cubic-bezier(.22,1,.36,1);
        }
        .notif.show { transform:translateX(0); }
        .notif-title { font-family:var(--fd); font-size:.85rem; font-weight:700; color:var(--white); margin-bottom:4px; }
        .notif-body  { font-size:.76rem; color:var(--muted); }
    </style>
</head>
<body>

<div id="map"></div>

<!-- Top HUD -->
<div class="hud-top">
    <a href="{{ route('driver.dashboard') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
    <div class="hud-route">
        <div class="hud-route-name">{{ $trip->route->name }}</div>
        <div class="hud-route-meta">
            <span class="live-dot"></span>
            <span id="gpsStatus">Acquiring GPS…</span>
            <span>·</span>
            <span>{{ $trip->vehicle->plate_number }}</span>
        </div>
    </div>
    <span class="chip" id="statusChip">Live</span>
</div>

<!-- GPS error -->
<div class="gps-banner" id="gpsBanner">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <span id="gpsErrMsg">GPS unavailable</span>
</div>

<!-- Bottom HUD -->
<div class="hud-bottom">
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-val teal" id="speedVal">0</div>
            <div class="stat-lbl">km/h</div>
        </div>
        <div class="stat-box">
            <div class="stat-val" id="pingVal">—</div>
            <div class="stat-lbl">Last ping</div>
        </div>
        <div class="stat-box">
            <div class="stat-val" id="accuracyVal">—</div>
            <div class="stat-lbl">Accuracy (m)</div>
        </div>
    </div>
    <div class="btn-row">
        <button class="btn btn-end" id="endBtn" onclick="endTrip()">
            <i class="bi bi-stop-fill"></i> End Trip
        </button>
        <button class="btn-sos" id="sosBtn" onclick="triggerSos()" title="Emergency SOS">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </button>
    </div>
</div>

<!-- Notification popup -->
<div class="notif" id="notif">
    <div class="notif-title" id="notifTitle"></div>
    <div class="notif-body"  id="notifBody"></div>
</div>

{{--
    Pre-build stops array in PHP — never use closures or array literals
    inside @json() in Blade; the template compiler misparses the brackets.
--}}
@php
    $stopsData = [];
    foreach ($trip->route->stops as $s) {
        $stopsData[] = [
            'name'  => $s->name,
            'lat'   => (float) $s->latitude,
            'lng'   => (float) $s->longitude,
            'order' => $s->order,
        ];
    }
@endphp

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// ── Constants ─────────────────────────────────────────────────────────────────

var TRIP_ID = {{ $trip->id }};
var CSRF    = document.querySelector('meta[name=csrf-token]').content;
var STOPS   = @json($stopsData);

// ── Map ───────────────────────────────────────────────────────────────────────
var map = L.map('map', { zoomControl: false, attributionControl: false })
           .setView([-1.9706, 30.1050], 14);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
L.control.zoom({ position: 'topright' }).addTo(map);

// ── Bus icon ──────────────────────────────────────────────────────────────────
var busIcon = L.divIcon({
    className: '',
    html: '<div style="width:44px;height:44px;border-radius:50%;background:#00E5C3;display:flex;align-items:center;justify-content:center;color:#050B18;font-size:1.2rem;font-weight:700;box-shadow:0 0 0 10px rgba(0,229,195,.15),0 0 0 20px rgba(0,229,195,.06);">🚌</div>',
    iconSize:   [44, 44],
    iconAnchor: [22, 22],
});

var busMarker  = null;
var pathCoords = [];
var polyline   = L.polyline([], { color: '#00E5C3', weight: 3, opacity: .7 }).addTo(map);

// ── Stop markers ──────────────────────────────────────────────────────────────
STOPS.forEach(function(s, i) {
    var isSchool = (i === STOPS.length - 1);
    var bg       = isSchool ? '#00E5C3' : '#0D1628';
    var border   = isSchool ? '#00E5C3' : 'rgba(0,229,195,.4)';
    var color    = isSchool ? '#050B18' : '#00E5C3';
    var label    = isSchool ? '🏫'      : s.order;

    var icon = L.divIcon({
        className: '',
        html: '<div style="width:28px;height:28px;border-radius:50%;background:' + bg + ';border:2px solid ' + border + ';display:flex;align-items:center;justify-content:center;color:' + color + ';font-size:.65rem;font-weight:700;">' + label + '</div>',
        iconSize:   [28, 28],
        iconAnchor: [14, 14],
    });

    L.marker([s.lat, s.lng], { icon: icon })
     .bindTooltip(s.name, { permanent: false, direction: 'top' })
     .addTo(map);
});

// ── GPS sharing ───────────────────────────────────────────────────────────────
var watchId   = null;
var pingCount = 0;

function startGPS() {
    if (!navigator.geolocation) {
        showBanner('Geolocation not supported on this device.');
        return;
    }
    watchId = navigator.geolocation.watchPosition(
        onPosition,
        onGpsError,
        { enableHighAccuracy: true, maximumAge: 0, timeout: 10000 }
    );
}

async function onPosition(pos) {
    var lat      = pos.coords.latitude;
    var lng      = pos.coords.longitude;
    var speed    = pos.coords.speed;
    var heading  = pos.coords.heading;
    var accuracy = pos.coords.accuracy;

    hideBanner();
    pingCount++;

    document.getElementById('speedVal').textContent    = speed    ? Math.round(speed * 3.6) : 0;
    document.getElementById('accuracyVal').textContent = accuracy ? Math.round(accuracy)    : '—';
    document.getElementById('pingVal').textContent     = new Date().toLocaleTimeString([], { hour:'2-digit', minute:'2-digit', second:'2-digit' });
    document.getElementById('gpsStatus').textContent   = 'Sharing · ' + pingCount + ' pings';

    if (!busMarker) {
        busMarker = L.marker([lat, lng], { icon: busIcon }).addTo(map);
        map.setView([lat, lng], 15);
    } else {
        busMarker.setLatLng([lat, lng]);
    }

    pathCoords.push([lat, lng]);
    polyline.setLatLngs(pathCoords);

    await fetch('/driver/trip/' + TRIP_ID + '/location', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body:    JSON.stringify({ latitude: lat, longitude: lng, speed: speed ? speed * 3.6 : 0, heading: heading, accuracy: accuracy }),
    }).catch(function() {});
}

function onGpsError(err) { showBanner('GPS error: ' + err.message); }
function showBanner(msg) { document.getElementById('gpsErrMsg').textContent = msg; document.getElementById('gpsBanner').classList.add('show'); }
function hideBanner()    { document.getElementById('gpsBanner').classList.remove('show'); }

// ── End trip ──────────────────────────────────────────────────────────────────
async function endTrip() {
    if (!confirm('End this trip? Location sharing will stop.')) return;
    if (watchId) navigator.geolocation.clearWatch(watchId);

    var btn = document.getElementById('endBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass"></i> Ending…';

    var res  = await fetch('/driver/trip/' + TRIP_ID + '/end', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    });
    var data = await res.json();
    if (data.ok) {
        showPopup('✅ Trip ended', 'Thanks! Your route is complete.');
        setTimeout(function() { location.href = '{{ route("driver.dashboard") }}'; }, 2000);
    } else {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-stop-fill"></i> End Trip';
    }
}

// ── SOS ───────────────────────────────────────────────────────────────────────
async function triggerSos() {
    if (!confirm('🚨 Send emergency SOS? Admins will be alerted immediately.')) return;
    var btn = document.getElementById('sosBtn');
    btn.disabled = true;
    await fetch('/driver/trip/' + TRIP_ID + '/sos', {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
        body:    JSON.stringify({ message: 'Driver triggered SOS from the app.' }),
    });
    showPopup('🚨 SOS sent', 'Admins have been notified with your location.');
    setTimeout(function() { btn.disabled = false; }, 5000);
}

// ── Notification popup ────────────────────────────────────────────────────────
function showPopup(title, body) {
    document.getElementById('notifTitle').textContent = title;
    document.getElementById('notifBody').textContent  = body;
    var el = document.getElementById('notif');
    el.classList.add('show');
    setTimeout(function() { el.classList.remove('show'); }, 4500);
}

// ── Boot ──────────────────────────────────────────────────────────────────────
startGPS();

document.getElementById('map').addEventListener('dblclick', function() {
    if (busMarker) map.setView(busMarker.getLatLng(), 16);
});
</script>
</body>
</html>