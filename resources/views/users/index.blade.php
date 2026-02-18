<x-app-layout>
    <div class="container mt-4">

        <div class="d-flex justify-content-between mb-3">
            <h4>User Management</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                + Add User
            </button>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th width="200">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        <!-- Edit Button -->
                        <button class="btn btn-sm btn-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#editModal{{ $user->id }}">
                            Edit
                        </button>

                        <!-- Delete Form -->
                        <form action="{{ route('users.destroy', $user->id) }}"
                            method="POST"
                            class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete this user?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- EDIT MODAL -->
                <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST"
                            action="{{ route('users.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit User</h5>
                                    <button type="button" class="btn-close"
                                        data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input type="text"
                                            name="name"
                                            class="form-control"
                                            value="{{ $user->name }}"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input type="email"
                                            name="email"
                                            class="form-control"
                                            value="{{ $user->email }}"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Role</label>
                                        <select name="role"
                                            class="form-control"
                                            required>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="driver" {{ $user->role == 'driver' ? 'selected' : '' }}>Driver</option>
                                            <option value="parent" {{ $user->role == 'parent' ? 'selected' : '' }}>Parent</option>
                                            <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit"
                                        class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @endforeach
            </tbody>
        </table>
    </div>

    <!-- CREATE MODAL -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create User</h5>
                        <button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text"
                                name="name"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email"
                                name="email"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Role</label>
                            <select name="role"
                                class="form-control"
                                required>
                                <option value="admin">Admin</option>
                                <option value="driver">Driver</option>
                                <option value="parent">Parent</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password"
                                name="password"
                                class="form-control"
                                required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit"
                            class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>