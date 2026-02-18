<x-app-layout>
<div class="container mt-4">
    <h4>Trip: {{ $trip->route->name }}</h4>
    <p>Vehicle: {{ $trip->vehicle->plate_number }} | Driver: {{ $trip->driver->name }}</p>

    <p class="text-info">Click "Start Trip" to share location and see live map.</p>
    <button id="startTrackingBtn" class="btn btn-success mb-3">Start Trip</button>

    <div id="map" style="width:100%; height:600px;"></div>
</div>

<!-- Leaflet -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// Initialize map
let map = L.map('map').setView([0,0], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'OpenStreetMap'
}).addTo(map);

// Marker for bus
let busMarker = L.marker([0,0]).addTo(map);

// Optional: Draw route polyline using stops if lat/lng stored
let routeStops = @json($routeStops); // array of stop names
let stopMarkers = [];

// You could replace stop names with coordinates if available
// Here we just place them with dummy coordinates for now
routeStops.forEach((stop, index)=>{
    let lat = 0 + index*0.001; // replace with real lat
    let lng = 0 + index*0.001; // replace with real lng
    let marker = L.marker([lat,lng]).addTo(map).bindPopup(stop);
    stopMarkers.push(marker);
});

// Function to send driver location to server
function startTracking() {
    if(navigator.geolocation){
        navigator.geolocation.watchPosition(function(position){
            let lat = position.coords.latitude;
            let lng = position.coords.longitude;

            // Update marker
            busMarker.setLatLng([lat,lng]);
            map.setView([lat,lng], 15);

            // Send to server
            fetch("{{ route('trips.updateLocation', $trip->id) }}", {
                method:'POST',
                headers:{
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },
                body: JSON.stringify({lat,lng})
            });

        }, function(error){
            alert('Error getting location: '+error.message);
        },{
            enableHighAccuracy:true,
            maximumAge:5000,
            timeout:10000
        });

        alert('Location sharing started!');
    } else {
        alert('Geolocation not supported.');
    }
}

document.getElementById('startTrackingBtn').addEventListener('click', startTracking);
</script>
</x-app-layout>
