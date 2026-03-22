<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route as TransportRoute;
use App\Models\Stop;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = TransportRoute::withCount(['stops', 'students'])
            ->with('trips', fn ($q) => $q->where('status', 'in_progress'))
            ->latest()
            ->paginate(10);

        return view('admin.routes.index', compact('routes'));
    }

    public function create()
    {
        return view('admin.routes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                   => 'required|string|max:100',
            'description'            => 'nullable|string|max:300',
            'type'                   => 'required|in:morning,afternoon,both',
            'morning_departure'      => 'nullable|date_format:H:i',
            'afternoon_departure'    => 'nullable|date_format:H:i',
            'estimated_duration_min' => 'nullable|integer|min:1',
            'total_distance_km'      => 'nullable|numeric|min:0',
            'is_active'              => 'boolean',
            'notes'                  => 'nullable|string|max:500',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $route = TransportRoute::create($data);

        return redirect()->route('admin.routes.show', $route)
            ->with('success', 'Route created. Now add stops below.');
    }

    public function show(TransportRoute $route)
    {
        $route->load([
            'stops' => fn ($q) => $q->orderBy('order'),
            'students.user',
            'trips' => fn ($q) => $q->with('driver.user', 'vehicle')->latest()->take(5),
        ]);

        return view('admin.routes.show', compact('route'));
    }

    public function edit(TransportRoute $route)
    {
        return view('admin.routes.edit', compact('route'));
    }

    public function update(Request $request, TransportRoute $route)
    {
        $data = $request->validate([
            'name'                   => 'required|string|max:100',
            'description'            => 'nullable|string|max:300',
            'type'                   => 'required|in:morning,afternoon,both',
            'morning_departure'      => 'nullable|date_format:H:i',
            'afternoon_departure'    => 'nullable|date_format:H:i',
            'estimated_duration_min' => 'nullable|integer|min:1',
            'total_distance_km'      => 'nullable|numeric|min:0',
            'is_active'              => 'boolean',
            'notes'                  => 'nullable|string|max:500',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $route->update($data);

        return redirect()->route('admin.routes.show', $route)
            ->with('success', 'Route updated.');
    }

    public function destroy(TransportRoute $route)
    {
        if ($route->trips()->where('status', 'in_progress')->exists()) {
            return back()->with('error', 'Route has an active trip in progress.');
        }

        $route->delete();

        return redirect()->route('admin.routes.index')
            ->with('success', 'Route deleted.');
    }

    // ── Stops ────────────────────────────────────────────────────

    public function storeStop(Request $request, TransportRoute $route)
    {
        $data = $request->validate([
            'name'               => 'required|string|max:150',
            'landmark'           => 'nullable|string|max:200',
            'latitude'           => 'required|numeric|between:-90,90',
            'longitude'          => 'required|numeric|between:-180,180',
            'arrival_offset_min' => 'required|integer|min:0',
            'dwell_time_sec'     => 'nullable|integer|min:0',
        ]);

        // Auto-assign the next order number
        $data['order']    = $route->stops()->max('order') + 1;
        $data['route_id'] = $route->id;

        Stop::create($data);

        return back()->with('success', 'Stop added.');
    }

    public function updateStop(Request $request, TransportRoute $route, Stop $stop)
    {
        $data = $request->validate([
            'name'               => 'required|string|max:150',
            'landmark'           => 'nullable|string|max:200',
            'latitude'           => 'required|numeric|between:-90,90',
            'longitude'          => 'required|numeric|between:-180,180',
            'arrival_offset_min' => 'required|integer|min:0',
            'dwell_time_sec'     => 'nullable|integer|min:0',
        ]);

        $stop->update($data);

        return back()->with('success', 'Stop updated.');
    }

    public function destroyStop(TransportRoute $route, Stop $stop)
    {
        $stop->delete();

        // Re-number remaining stops sequentially
        $route->stops()->orderBy('order')->each(function ($s, $i) {
            $s->update(['order' => $i + 1]);
        });

        return back()->with('success', 'Stop removed.');
    }

    public function reorderStops(Request $request, TransportRoute $route)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:stops,id',
        ]);

        foreach ($request->order as $position => $stopId) {
            Stop::where('id', $stopId)
                ->where('route_id', $route->id)
                ->update(['order' => $position + 1]);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * GET /admin/routes/{route}/stops-json
     * Returns ordered stops for a route as JSON.
     * Used by the student create/edit form to dynamically populate the stop dropdown.
     */
    public function stopsJson(TransportRoute $route): \Illuminate\Http\JsonResponse
    {
        $stops = $route->stops()
            ->orderBy('order')
            ->get(['id', 'name', 'order', 'landmark'])
            ->map(function ($s) {
                return [
                    'id'       => $s->id,
                    'name'     => $s->name,
                    'order'    => $s->order,
                    'landmark' => $s->landmark,
                ];
            });

        return response()->json($stops);
    }
}