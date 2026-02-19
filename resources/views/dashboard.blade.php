<x-app-layout>
<div class="container">
    <h1 class="mb-4">Dashboard</h1>

    <div class="row">
        <!-- Summary Cards -->
        <div class="col-md-2"><div class="card p-3">Users: {{ $totalUsers }}</div></div>
        <div class="col-md-2"><div class="card p-3">Vehicles: {{ $totalVehicles }}</div></div>
        <div class="col-md-2"><div class="card p-3">Trips: {{ $totalTrips }}</div></div>
        <div class="col-md-2"><div class="card p-3">Routes: {{ $totalRoutes }}</div></div>
        <div class="col-md-2"><div class="card p-3">Subscriptions: {{ $totalSubscriptions }}</div></div>
    </div>

    <div class="row mt-4">
        <!-- Trips Status Chart -->
        <div class="col-md-6">
            <canvas id="tripsStatusChart"></canvas>
        </div>

        <!-- Subscriptions per Route Chart -->
        <div class="col-md-6">
            <canvas id="subscriptionsChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Trips Status Chart
    const tripsStatusCtx = document.getElementById('tripsStatusChart').getContext('2d');
    new Chart(tripsStatusCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($tripsByStatus->keys()) !!},
            datasets: [{
                data: {!! json_encode($tripsByStatus->values()) !!},
                backgroundColor: ['#36A2EB', '#FFCE56', '#FF6384'],
            }]
        }
    });

    // Subscriptions per Route Chart
    const subscriptionsCtx = document.getElementById('subscriptionsChart').getContext('2d');
    new Chart(subscriptionsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($subscriptionsPerRoute->keys()) !!},
            datasets: [{
                label: 'Subscriptions',
                data: {!! json_encode($subscriptionsPerRoute->values()) !!},
                backgroundColor: '#4BC0C0'
            }]
        },
        options: { responsive: true }
    });
</script>
</x-app-layout>
