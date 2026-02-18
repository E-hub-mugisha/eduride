<?php

namespace App\Http\Controllers;

use App\Mail\BusNearStopMail;
use App\Mail\TripCompletedMail;
use App\Mail\TripStartedMail;
use App\Models\ParentTripSubscription;
use App\Models\Route;
use App\Models\Trip;
use App\Models\User;
use App\Notifications\BusNearStopNotification;
use App\Notifications\TripCompletedNotification;
use App\Notifications\TripDelayNotification;
use App\Notifications\TripStartedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TripController extends Controller
{
    public function index()
    {
        $trips = Trip::latest()->paginate(10);
        return view('trips.index', compact('trips'));
    }

    public function start(Route $route)
    {
        // Ensure route has vehicle & driver
        if (!$route->vehicle_id || !$route->driver_id) {
            return redirect()->back()->with('error', 'Assign vehicle & driver first.');
        }

        // Create new trip
        $trip = Trip::create([
            'route_id'   => $route->id,
            'vehicle_id' => $route->vehicle_id,
            'driver_id'  => $route->driver_id,
            'status'     => 'in_progress',
            'start_time' => now(),
        ]);

        // Load subscriptions with parent and child
        $subscriptions = ParentTripSubscription::where('route_id', $route->id)
            ->with(['parent', 'student'])
            ->get();

        foreach ($subscriptions as $sub) {
            if ($sub->parent?->email && $sub->student) {
                Log::info('Sending TripStartedMail to: ' . $sub->parent->email);
                Mail::to($sub->parent->email)
                    ->send(new TripStartedMail($trip, $sub->student));
            }
        }

        return redirect()->route('trips.driverStart', $trip->id)
            ->with('success', 'Trip started. Parents notified.');
    }

    public function end(Trip $trip)
    {
        // Update trip status
        $trip->update([
            'status' => 'completed',
            'end_time' => now(),
        ]);

        $subscriptions = ParentTripSubscription::where('route_id', $trip->route_id)
            ->with(['parent', 'student'])
            ->get();

        foreach ($subscriptions as $sub) {
            if ($sub->parent?->email && $sub->student) {
                Log::info('Sending TripCompletedMail to: '.$sub->parent->email);
                Mail::to($sub->parent->email)
                    ->send(new TripCompletedMail($trip, $sub->student));
            }
        }

        return redirect()->back()->with('success', 'Trip ended and parents notified.');
    }

    public function updateLocation(Request $request, Trip $trip)
    {
        $trip->update([
            'current_lat' => $request->lat,
            'current_lng' => $request->lng
        ]);
        $stops = $trip->route->stops;

        foreach ($trip->stops as $stop) {

            $eta = $this->calculateDistance(
                $trip->current_lat,
                $trip->current_lng,
                $stop->lat,
                $stop->lng
            );

            if ($eta <= 5) {

                foreach ($stop->parents as $parent) {
                    Mail::to($parent->email)
                        ->send(new BusNearStopMail($trip, $stop, $eta));
                }
            }
        }

        if ($trip->start_time && now()->diffInMinutes($trip->start_time) > 90) {

            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                $admin->notify(new TripDelayNotification($trip));
            }
        }
        return response()->json(['success' => true]);
    }

    public function getLocation(Trip $trip)
    {
        return response()->json([
            'lat' => $trip->current_lat,
            'lng' => $trip->current_lng
        ]);
    }

    public function showMap(Trip $trip)
    {
        // Make sure route stops are included
        $routeStops = $trip->route->stops ? json_decode($trip->route->stops, true) : [];

        return view('trips.map', compact('trip', 'routeStops'));
    }

    public function driverStart(Trip $trip)
    {
        // Include route stops if you want to draw a path
        $routeStops = [];
        if ($trip->route && $trip->route->stops) {
            $routeStops = explode(',', $trip->route->stops); // Convert comma-separated stops to array
        }
        return view('trips.driver_start', compact('trip', 'routeStops'));
    }

    private function calculateDistance($currentLat, $currentLng, $stopLat, $stopLng)
    {
        $distance = sqrt(
            pow($stopLat - $currentLat, 2) +
                pow($stopLng - $currentLng, 2)
        );

        $averageSpeed = 40; // km per hour

        return ($distance / $averageSpeed) * 60; // minutes
    }
}
