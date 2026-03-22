<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Route as TransportRoute;
use App\Models\Trip;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function index(Request $request)
    {
        $trips = Trip::with(['route', 'driver.user', 'vehicle'])
            ->when($request->status,   fn ($q) => $q->where('status', $request->status))
            ->when($request->route_id, fn ($q) => $q->where('route_id', $request->route_id))
            ->when($request->date,     fn ($q) => $q->whereDate('scheduled_at', $request->date))
            ->latest('scheduled_at')
            ->paginate(15);

        $routes   = TransportRoute::active()->orderBy('name')->get();
        $statuses = ['scheduled', 'in_progress', 'completed', 'cancelled', 'delayed'];

        return view('admin.trips.index', compact('trips', 'routes', 'statuses'));
    }

    public function create()
    {
        $routes  = TransportRoute::active()->orderBy('name')->get();
        $drivers = Driver::with(['user', 'vehicle'])
            ->whereIn('status', ['available', 'off_duty'])
            ->get();
        $vehicles = Vehicle::active()->orderBy('plate_number')->get();

        return view('admin.trips.create', compact('routes', 'drivers', 'vehicles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'route_id'     => 'required|exists:routes,id',
            'driver_id'    => 'required|exists:drivers,id',
            'vehicle_id'   => 'required|exists:vehicles,id',
            'type'         => 'required|in:morning,afternoon,special',
            'scheduled_at' => 'required|date|after_or_equal:today',
            'notes'        => 'nullable|string|max:500',
        ]);

        $data['status'] = 'scheduled';

        Trip::create($data);

        return redirect()->route('admin.trips.index')
            ->with('success', 'Trip scheduled successfully. The driver will see it on their dashboard.');
    }

    public function show(Trip $trip)
    {
        $trip->load([
            'route.stops',
            'driver.user',
            'vehicle',
            'locations',
            'notifications' => fn ($q) => $q->with('user')->latest()->take(20),
        ]);

        // Build polyline as plain array — no closures inside @json() in Blade
        $polylineArray = [];
        foreach ($trip->locations as $loc) {
            $polylineArray[] = $loc->toMapPoint();
        }
        $polyline = collect($polylineArray);

        return view('admin.trips.show', compact('trip', 'polyline'));
    }

    public function track(Trip $trip)
    {
        $trip->load(['route.stops', 'driver.user', 'vehicle', 'locations']);

        $stops = [];
        foreach ($trip->route->stops as $s) {
            $stops[] = [
                'id'    => $s->id,
                'name'  => $s->name,
                'lat'   => (float) $s->latitude,
                'lng'   => (float) $s->longitude,
                'order' => $s->order,
            ];
        }

        // Build polyline from recorded GPS pings (historic path)
        $polyline = [];
        foreach ($trip->locations as $loc) {
            $polyline[] = $loc->toMapPoint();
        }

        return view('admin.trips.track', compact('trip', 'stops', 'polyline'));
    }

    public function destroy(Trip $trip)
    {
        if ($trip->isInProgress()) {
            return back()->with('error', 'Cannot delete a trip that is currently in progress.');
        }

        $trip->locations()->delete();
        $trip->notifications()->delete();
        $trip->delete();

        return redirect()->route('admin.trips.index')
            ->with('success', 'Trip deleted.');
    }

    public function position(Trip $trip)
    {
        return response()->json([
            'status'              => $trip->status,
            'lat'                 => $trip->current_latitude  ? (float) $trip->current_latitude  : null,
            'lng'                 => $trip->current_longitude ? (float) $trip->current_longitude : null,
            'speed'               => $trip->current_speed,
            'location_updated_at' => $trip->location_updated_at?->toIso8601String(),
            'is_stale'            => $trip->is_location_stale,
            'delay_minutes'       => $trip->delay_minutes,
        ]);
    }
}