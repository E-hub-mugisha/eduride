<x-app-layout>
    <div class="container mt-4">
        <h4>Parent Trip Subscriptions</h4>

        <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#subscriptionModal"
            onclick="openSubscriptionModal()">+ Add Subscription</button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Parent</th>
                    <th>Student</th>
                    <th>Route</th>
                    <th>Stop Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscriptions as $sub)
                <tr>
                    <td>{{ $sub->parent->name }}</td>
                    <td>{{ $sub->student->full_name ?? '-' }}</td>
                    <td>{{ $sub->route->name ?? '-' }}</td>
                    <td>{{ $sub->stop_name ?? '-' }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning"
                            data-bs-toggle="modal" data-bs-target="#subscriptionModal"
                            onclick="openSubscriptionModal({{ $sub }})">Edit</button>

                        <form action="{{ route('subscriptions.destroy',$sub->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete subscription?')">Delete</button>
                        </form>
                        @php
                        $trip = \App\Models\Trip::where('route_id', $sub->route_id)
                        ->where('status', 'in_progress')
                        ->first();
                        @endphp

                        @if($trip)
                        <a href="{{ route('trips.showMap', $trip) }}" class="btn btn-sm btn-info">View Bus</a>
                        @endif

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Subscription Modal -->
    <div class="modal fade" id="subscriptionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="subscriptionForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="subscription_method" value="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="subscriptionModalTitle">Add Subscription</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Parent</label>
                            <select name="parent_id" id="parent_id" class="form-control" required>
                                @foreach($parents as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <label>Student</label>
                            <select name="child_id" id="child_id" class="form-control">
                                <option value="">-- Optional --</option>
                                @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <label>Route</label>
                            <select name="route_id" id="route_id" class="form-control" required>
                                @foreach($routes as $route)
                                <option value="{{ $route->id }}">{{ $route->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <label>Stop Name</label>
                            <input type="text" name="stop_name" id="stop_name" class="form-control">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-success" type="submit">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        function openSubscriptionModal(sub = null) {
            const form = document.getElementById('subscriptionForm');
            const title = document.getElementById('subscriptionModalTitle');
            const methodInput = document.getElementById('subscription_method');

            if (sub) {
                title.innerText = "Edit Subscription";
                form.action = `/subscriptions/${sub.id}`;
                methodInput.value = "PUT";
                document.getElementById('parent_id').value = sub.parent_id;
                document.getElementById('child_id').value = sub.child_id ?? '';
                document.getElementById('route_id').value = sub.route_id;
                document.getElementById('stop_name').value = sub.stop_name ?? '';
            } else {
                title.innerText = "Add Subscription";
                form.action = `/subscriptions`;
                methodInput.value = "POST";
                document.getElementById('parent_id').value = '';
                document.getElementById('child_id').value = '';
                document.getElementById('route_id').value = '';
                document.getElementById('stop_name').value = '';
            }
        }
    </script>
</x-app-layout>