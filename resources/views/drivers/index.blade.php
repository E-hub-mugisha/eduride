<x-app-layout>
<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h4>Driver Management</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            + Add Driver
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Vehicle</th>
                <th width="200">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($drivers as $driver)
            <tr>
                <td>{{ $driver->name }}</td>
                <td>{{ $driver->email }}</td>
                <td>{{ $driver->phone }}</td>
                <td>{{ $driver->vehicle ? $driver->vehicle->plate_number : '-' }}</td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                            data-bs-target="#editModal{{ $driver->id }}">Edit</button>

                    <form action="{{ route('drivers.destroy', $driver->id) }}"
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete this driver?')">Delete</button>
                    </form>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal{{ $driver->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('drivers.update', $driver->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Driver</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $driver->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $driver->email }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $driver->phone }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Assign Vehicle</label>
                                    <select name="vehicle_id" class="form-control">
                                        <option value="">-- None --</option>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" {{ $driver->vehicle && $driver->vehicle->id==$vehicle->id ? 'selected':'' }}>
                                                {{ $vehicle->plate_number }} ({{ ucfirst($vehicle->status) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update Driver</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @endforeach
        </tbody>
    </table>

    {{ $drivers->links() }}

</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('drivers.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Driver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Assign Vehicle</label>
                        <select name="vehicle_id" class="form-control">
                            <option value="">-- None --</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->plate_number }} ({{ ucfirst($vehicle->status) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Driver</button>
                </div>
            </div>
        </form>
    </div>
</div>

</x-app-layout>
