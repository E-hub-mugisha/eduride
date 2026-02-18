<x-app-layout>
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
</x-app-layout>