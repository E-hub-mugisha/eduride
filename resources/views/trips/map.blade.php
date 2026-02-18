<x-app-layout>
<div class="container mt-4">
    <h4>Trip Map - {{ $trip->route->name }}</h4>
    <p>Driver: {{ $trip->driver->name }} | Vehicle: {{ $trip->vehicle->plate_number }}</p>

    <div id="map" style="width:100%;height:600px;"></div>
    <a href="{{ route('trips.index') }}" class="btn btn-secondary mt-3">Back to Trips</a>
    @if($trip->status=='in_progress')
        <form method="POST" action="{{ route('trips.end',$trip) }}">
            @csrf
            <button class="btn btn-success mt-2">Complete Trip</button>
        </form>
    @endif
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let initialLat = {{ $trip->current_lat ?? 0 }};
let initialLng = {{ $trip->current_lng ?? 0 }};

// Initialize map
let map = L.map('map').setView([initialLat, initialLng], 15);

// Add OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution:'OpenStreetMap'
}).addTo(map);

// Use standard Leaflet marker icon
let busIcon = L.icon({
    iconUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-icon-2x.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowUrl: 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/images/marker-shadow.png',
    shadowSize: [41, 41]
});

// Add bus marker
let busMarker = L.marker([initialLat, initialLng], {icon: busIcon}).addTo(map);

// Draw route polyline & stops
let routeStops = @json($routeStops ?? []);
let latlngs = [];

routeStops.forEach(stop=>{
    if(stop.lat && stop.lng){
        L.marker([stop.lat, stop.lng]).addTo(map).bindPopup(stop.name);
        latlngs.push([stop.lat, stop.lng]);
    }
});

// Fit map to route bounds
if(latlngs.length > 0){
    L.polyline(latlngs, {color:'blue', weight:3}).addTo(map);
    map.fitBounds(latlngs);
}

// Poll every 5 seconds to update bus location
setInterval(()=>{
    fetch("{{ route('trips.getLocation', $trip->id) }}")
        .then(res => res.json())
        .then(data => {
            if(data.lat && data.lng && data.lat != 0 && data.lng != 0){
                busMarker.setLatLng([data.lat, data.lng]);
                map.setView([data.lat, data.lng], map.getZoom());
            }
        })
        .catch(err => console.error('Error fetching location:', err));
}, 5000);
</script>
</x-app-layout>
