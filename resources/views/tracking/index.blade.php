<x-app-layout>
<div class="container-fluid px-4 py-4">
    <div id="map" class="w-full h-[80vh] rounded-xl shadow-lg"></div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
const map = L.map('map').setView([-1.9577, 30.1127], 12);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

let markers = {};
let polylines = {};

async function fetchTrips() {
    const response = await fetch('{{ route("api.trips") }}');
    const trips = await response.json();

    trips.forEach(trip => {
        const { id, vehicle, driver, route, current_lat, current_lng } = trip;

        // Marker
        const popupText = `<b>${vehicle.plate_number}</b><br>${driver?.name || 'N/A'}<br>${route.name}`;
        if (markers[id]) {
            markers[id].setLatLng([current_lat, current_lng]);
        } else {
            markers[id] = L.marker([current_lat, current_lng]).addTo(map).bindPopup(popupText);
        }

        // Polyline
        const latlngs = [[current_lat, current_lng]]; // start with current location
        if (route.polyline) {
            route.polyline.forEach(p => {
                if (p) latlngs.push(p);
            });
        }

        if (polylines[id]) {
            polylines[id].setLatLngs(latlngs);
        } else {
            polylines[id] = L.polyline(latlngs, { color: 'blue' }).addTo(map);
        }
    });
}

fetchTrips();
setInterval(fetchTrips, 5000);
</script>

</x-app-layout>
