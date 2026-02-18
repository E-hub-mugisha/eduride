<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('tracking.index');
    }

    public function tripsApi()
    {
        $trips = Trip::whereNotNull('current_lat')
            ->whereNotNull('current_lng')
            ->whereIn('status', ['in_progress', 'pending'])
            ->with(['vehicle', 'driver', 'route'])
            ->get();

        // Transform trips for frontend
        $data = $trips->map(function ($trip) {
            $route = $trip->route;

            // Ensure stops are always an array
            $stops = $route->stops_array ?? [];

            // Full polyline: start -> stops -> end
            $polyline = array_merge(
                [[$route->start_point_lat ?? (float) $trip->current_lat, $route->start_point_lng ?? (float) $trip->current_lng]],
                $stops_coordinates = array_map(function ($stop) {
                    // For simplicity, we just return null coordinates for stops; frontend can show markers
                    return null;
                }, $stops),
                [[$route->end_point_lat ?? (float) $trip->current_lat, $route->end_point_lng ?? (float) $trip->current_lng]]
            );

            return [
                'id' => $trip->id,
                'status' => $trip->status,
                'current_lat' => (float) $trip->current_lat,
                'current_lng' => (float) $trip->current_lng,
                'vehicle' => $trip->vehicle,
                'driver' => $trip->driver,
                'route' => [
                    'id' => $route->id,
                    'name' => $route->name,
                    'start_point' => $route->start_point,
                    'end_point' => $route->end_point,
                    'stops' => $stops,
                    'polyline' => $polyline
                ],
            ];
        });

        return response()->json($data);
    }
}
