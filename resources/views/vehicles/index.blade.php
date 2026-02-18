<x-app-layout>
<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h4>Vehicle Management</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            + Add Vehicle
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Plate Number</th>
                <th>Model</th>
                <th>Capacity</th>
                <th>Status</th>
                <th width="200">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicles as $vehicle)
            <tr>
                <td>{{ $vehicle->plate_number }}</td>
                <td>{{ $vehicle->model }}</td>
                <td>{{ $vehicle->capacity }}</td>
                <td>{{ ucfirst($vehicle->status) }}</td>
                <td>
                    <!-- Edit -->
                    <button class="btn btn-sm btn-warning"
                        data-bs-toggle="modal"
                        data-bs-target="#editModal{{ $vehicle->id }}">
                        Edit
                    </button>

                    <!-- Delete -->
                    <form action="{{ route('vehicles.destroy', $vehicle->id) }}"
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete this vehicle?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>

            <!-- EDIT MODAL -->
            <div class="modal fade" id="editModal{{ $vehicle->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('vehicles.update', $vehicle->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Vehicle</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Plate Number</label>
                                    <input type="text" name="plate_number"
                                           class="form-control"
                                           value="{{ $vehicle->plate_number }}" required>
                                </div>

                                <div class="mb-3">
                                    <label>Model</label>
                                    <input type="text" name="model"
                                           class="form-control"
                                           value="{{ $vehicle->model }}" required>
                                </div>

                                <div class="mb-3">
                                    <label>Capacity</label>
                                    <input type="number" name="capacity"
                                           class="form-control"
                                           value="{{ $vehicle->capacity }}" required>
                                </div>

                                <div class="mb-3">
                                    <label>Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="active" {{ $vehicle->status=='active'?'selected':'' }}>Active</option>
                                        <option value="maintenance" {{ $vehicle->status=='maintenance'?'selected':'' }}>Maintenance</option>
                                        <option value="inactive" {{ $vehicle->status=='inactive'?'selected':'' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update Vehicle</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @endforeach
        </tbody>
    </table>

    {{ $vehicles->links() }}

</div>

<!-- CREATE MODAL -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('vehicles.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Plate Number</label>
                        <input type="text" name="plate_number" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Model</label>
                        <input type="text" name="model" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Capacity</label>
                        <input type="number" name="capacity" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="active">Active</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Vehicle</button>
                </div>
            </div>
        </form>
    </div>
</div>

</x-app-layout>
