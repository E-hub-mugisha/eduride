<x-app-layout>
    <div class="container mt-4">

        <div class="d-flex justify-content-between mb-3">
            <h4>Route Management</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">+ Add Route</button>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Stops</th>
                    <th>Vehicle</th>
                    <th>Driver</th>
                    <th>Status</th>
                    <th width="200">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($routes as $route)
                <tr>
                    <td>{{ $route->name }}</td>
                    <td>{{ $route->start_point }}</td>
                    <td>{{ $route->end_point }}</td>
                    <td>{{ $route->stops }}</td>
                    <td>{{ $route->vehicle ? $route->vehicle->plate_number : '-' }}</td>
                    <td>{{ $route->driver ? $route->driver->name : '-' }}</td>
                    <td>{{ ucfirst($route->status) }}</td>
                    <td>
                        @if($route->vehicle && $route->driver)
                        <form method="POST" action="{{ route('trips.start', $route->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-success">Start Trip</button>
                        </form>
                        @else
                        <span class="text-muted">Assign vehicle/driver</span>
                        @endif
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                            data-bs-target="#editModal{{ $route->id }}">Edit</button>

                        <form action="{{ route('routes.destroy', $route->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this route?')">Delete</button>
                        </form>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal{{ $route->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('routes.update', $route->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Route</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ $route->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Start Point</label>
                                        <input type="text" name="start_point" class="form-control" value="{{ $route->start_point }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>End Point</label>
                                        <input type="text" name="end_point" class="form-control" value="{{ $route->end_point }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Stops (comma-separated)</label>
                                        <input type="text" name="stops" class="form-control" value="{{ $route->stops }}">
                                    </div>
                                    <div class="mb-3">
                                        <label>Vehicle</label>
                                        <select name="vehicle_id" class="form-control">
                                            <option value="">-- None --</option>
                                            @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" {{ $route->vehicle && $route->vehicle->id==$vehicle->id?'selected':'' }}>
                                                {{ $vehicle->plate_number }} ({{ ucfirst($vehicle->status) }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Driver</label>
                                        <select name="driver_id" class="form-control">
                                            <option value="">-- None --</option>
                                            @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" {{ $route->driver && $route->driver->id==$driver->id?'selected':'' }}>
                                                {{ $driver->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="pending" {{ $route->status=='pending'?'selected':'' }}>Pending</option>
                                            <option value="active" {{ $route->status=='active'?'selected':'' }}>Active</option>
                                            <option value="completed" {{ $route->status=='completed'?'selected':'' }}>Completed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update Route</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @endforeach
            </tbody>
        </table>

        {{ $routes->links() }}

    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('routes.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Route</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Start Point</label>
                            <input type="text" name="start_point" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>End Point</label>
                            <input type="text" name="end_point" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Stops (comma-separated)</label>
                            <input type="text" name="stops" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Vehicle</label>
                            <select name="vehicle_id" class="form-control">
                                <option value="">-- None --</option>
                                @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->plate_number }} ({{ ucfirst($vehicle->status) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Driver</label>
                            <select name="driver_id" class="form-control">
                                <option value="">-- None --</option>
                                @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Route</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>