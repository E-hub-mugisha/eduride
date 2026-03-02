@extends('layouts.app')
@section('content')

<style>
    /* Stat Cards */
.stat-card {
    border: none;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 8px 24px rgba(0,0,0,.06);
    transition: transform .2s ease, box-shadow .2s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,.12);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    color: #fff;
}

/* Color themes */
.stat-users .stat-icon {
    background: linear-gradient(135deg, #5f6cff, #7a88ff);
}

.stat-vehicles .stat-icon {
    background: linear-gradient(135deg, #00c6ff, #0072ff);
}

.stat-trips .stat-icon {
    background: linear-gradient(135deg, #ff9f43, #ff6f00);
}

.stat-routes .stat-icon {
    background: linear-gradient(135deg, #28c76f, #1ea65a);
}

.stat-subscriptions .stat-icon {
    background: linear-gradient(135deg, #7367f0, #9c8cff);
}

.card canvas {
    max-height: 280px;
}
</style>

<div class="container">
    <h1 class="mb-4">Dashboard</h1>

    <div class="row g-4">

    <!-- Users -->
    <div class="col-xl col-md-6">
        <div class="card stat-card stat-users">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Users</h6>
                    <h3 class="mb-0 fw-bold">{{ $totalUsers }}</h3>
                </div>
                <div class="stat-icon">
                    <iconify-icon icon="solar:users-group-rounded-line-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicles -->
    <div class="col-xl col-md-6">
        <div class="card stat-card stat-vehicles">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Vehicles</h6>
                    <h3 class="mb-0 fw-bold">{{ $totalVehicles }}</h3>
                </div>
                <div class="stat-icon">
                    <iconify-icon icon="solar:bus-line-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>

    <!-- Trips -->
    <div class="col-xl col-md-6">
        <div class="card stat-card stat-trips">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Trips</h6>
                    <h3 class="mb-0 fw-bold">{{ $totalTrips }}</h3>
                </div>
                <div class="stat-icon">
                    <iconify-icon icon="solar:route-line-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>

    <!-- Routes -->
    <div class="col-xl col-md-6">
        <div class="card stat-card stat-routes">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Routes</h6>
                    <h3 class="mb-0 fw-bold">{{ $totalRoutes }}</h3>
                </div>
                <div class="stat-icon">
                    <iconify-icon icon="solar:map-point-line-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscriptions -->
    <div class="col-xl col-md-6">
        <div class="card stat-card stat-subscriptions">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted mb-1">Subscriptions</h6>
                    <h3 class="mb-0 fw-bold">{{ $totalSubscriptions }}</h3>
                </div>
                <div class="stat-icon">
                    <iconify-icon icon="solar:wallet-line-duotone"></iconify-icon>
                </div>
            </div>
        </div>
    </div>

</div>

    <div class="row mt-4 align-items-stretch">

    <!-- Trips Status Chart -->
    <div class="col-md-6 d-flex">
        <div class="card w-100 p-4 d-flex flex-column">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Trips by Status</h5>
                <a href="{{ route('trips.index') }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>

            <!-- Chart wrapper fills remaining space -->
            <div class="flex-grow-1">
                <canvas id="tripsStatusChart"></canvas>
            </div>

        </div>
    </div>

    <!-- Subscriptions per Route Chart -->
    <div class="col-md-6 d-flex">
        <div class="card w-100 p-4 d-flex flex-column">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Subscriptions per Route</h5>
                <a href="{{ route('routes.index') }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>

            <div class="flex-grow-1">
                <canvas id="subscriptionsChart"></canvas>
            </div>

        </div>
    </div>

</div>
</div>
<div class="container mt-4">

    <h4>Trip Management & GPS Tracking</h4>

    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>Route</th>
                <th>Vehicle</th>
                <th>Driver</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trips as $trip)
            <tr>
                <td>{{ $trip->route->name }}</td>
                <td>{{ $trip->vehicle->plate_number }}</td>
                <td>{{ $trip->driver->name }}</td>
                <td>{{ ucfirst($trip->status) }}</td>
                <td>
                    @if($trip->status=='in_progress')
                    <form method="POST" action="{{ route('trips.end',$trip->id) }}">
                        @csrf
                        <button class="btn btn-sm btn-danger">End Trip</button>
                    </form>
                    @endif
                    <a href="{{ route('trips.showMap', $trip->id) }}" class="btn btn-sm btn-primary">
                        View Map
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

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

@endsection
