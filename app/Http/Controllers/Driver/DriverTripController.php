<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\TransportNotification;
use App\Models\Trip;
use App\Models\TripLocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverTripController extends Controller
{
    /**
     * Driver's home screen — shows today's assigned trips.
     */
    public function dashboard()
    {
        $driver = auth()->user()->driver;

        abort_unless($driver, 403, 'No driver profile linked to this account.');

        // Today's trips
        $todayTrips = Trip::with(['route.stops', 'vehicle'])
            ->forDriver($driver->id)
            ->whereDate('scheduled_at', today())
            ->orderBy('scheduled_at')
            ->get();

        $activeTrip = $todayTrips->firstWhere('status', 'in_progress');

        // ── My Trips — recent history (last 30) ──────────────────
        $myTrips = Trip::with(['route', 'vehicle'])
            ->forDriver($driver->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderByDesc('scheduled_at')
            ->take(30)
            ->get();

        // ── Analytics ────────────────────────────────────────────

        // Trips per day for the last 7 days
        $tripsPerDay = Trip::forDriver($driver->id)
            ->where('status', 'completed')
            ->whereDate('scheduled_at', '>=', now()->subDays(6))
            ->selectRaw('DATE(scheduled_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // Fill missing days with 0
        $chartLabels = [];
        $chartData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date          = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('D');
            $chartData[]   = (int) ($tripsPerDay[$date] ?? 0);
        }

        // Summary stats
        $totalTrips     = Trip::forDriver($driver->id)->where('status', 'completed')->count();
        $tripsThisMonth = Trip::forDriver($driver->id)
            ->where('status', 'completed')
            ->whereMonth('scheduled_at', now()->month)
            ->whereYear('scheduled_at', now()->year)
            ->count();
        $tripsThisWeek  = Trip::forDriver($driver->id)
            ->where('status', 'completed')
            ->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        return view('driver.dashboard', compact(
            'driver', 'todayTrips', 'activeTrip',
            'myTrips', 'chartLabels', 'chartData',
            'totalTrips', 'tripsThisMonth', 'tripsThisWeek',
        ));
    }

    public function show(Trip $trip)
    {
        $this->authoriseDriver($trip);

        $trip->load(['route.stops', 'vehicle', 'locations' => fn ($q) => $q->orderByDesc('recorded_at')->take(1)]);

        return view('driver.trip', compact('trip'));
    }

    /**
     * POST /driver/trip/{trip}/start
     * Marks the trip as in_progress. Returns JSON for the JS fetch call.
     */
    public function start(Trip $trip): JsonResponse
    {
        $this->authoriseDriver($trip);

        if (! $trip->isScheduled()) {
            return response()->json(['error' => 'Trip cannot be started in its current state.'], 422);
        }

        $trip->start();

        // Notify all parents on this route
        $this->notifyParents($trip, 'trip_started',
            '🚌 Trip started',
            "The bus for {$trip->route->name} has departed. Driver: {$trip->driver->name}."
        );

        return response()->json(['ok' => true, 'trip_id' => $trip->id]);
    }

    /**
     * POST /driver/trip/{trip}/end
     */
    public function end(Trip $trip): JsonResponse
    {
        $this->authoriseDriver($trip);

        if (! $trip->isInProgress()) {
            return response()->json(['error' => 'Trip is not in progress.'], 422);
        }

        $trip->end();

        $this->notifyParents($trip, 'trip_completed',
            '✅ Trip completed',
            "The bus for {$trip->route->name} has completed its route."
        );

        return response()->json(['ok' => true]);
    }

    /**
     * POST /driver/trip/{trip}/location
     *
     * Receives a single GPS ping from the driver's browser.
     * Body: { latitude, longitude, speed?, heading?, accuracy? }
     *
     * Also checks if the bus just arrived near any route stop
     * and fires parent notifications accordingly.
     */
    public function location(Request $request, Trip $trip): JsonResponse
    {
        $this->authoriseDriver($trip);

        if (! $trip->isInProgress()) {
            return response()->json(['error' => 'Trip not active.'], 422);
        }

        $data = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'speed'     => 'nullable|numeric|min:0',
            'heading'   => 'nullable|numeric|min:0|max:360',
            'accuracy'  => 'nullable|numeric|min:0',
        ]);

        // 1. Write the raw ping
        TripLocation::create(array_merge($data, [
            'trip_id'     => $trip->id,
            'recorded_at' => now(),
        ]));

        // 2. Update the denormalised "current position" on the trip row
        $trip->updateLocation(
            $data['latitude'],
            $data['longitude'],
            $data['speed']   ?? 0,
            $data['heading'] ?? 0,
        );

        // 3. Check proximity to upcoming stops → notify parents
        $this->checkStopProximity($trip, $data['latitude'], $data['longitude']);

        return response()->json(['ok' => true, 'recorded_at' => now()->toIso8601String()]);
    }

    /**
     * POST /driver/trip/{trip}/sos
     * Emergency alert — notifies all admins immediately.
     */
    public function sos(Request $request, Trip $trip): JsonResponse
    {
        $this->authoriseDriver($trip);

        $message = $request->input('message', 'Emergency SOS triggered by driver.');

        // Notify all admins
        \App\Models\User::admins()->each(function ($admin) use ($trip, $message) {
            TransportNotification::send(
                user:    $admin,
                type:    'sos',
                title:   '🚨 SOS — ' . $trip->driver->name,
                message: $message . " · Route: {$trip->route->name} · Vehicle: {$trip->vehicle->plate_number}",
                trip:    $trip,
                meta:    [
                    'lat' => $trip->current_latitude,
                    'lng' => $trip->current_longitude,
                ],
            );
        });

        return response()->json(['ok' => true]);
    }

    // ── Private helpers ──────────────────────────────────────────

    /**
     * Abort if the authenticated driver doesn't own this trip.
     */
    private function authoriseDriver(Trip $trip): void
    {
        $driver = auth()->user()->driver;
        abort_if(! $driver || $trip->driver_id !== $driver->id, 403);
    }

    /**
     * Fire a notification to every parent whose child is on this route.
     */
    private function notifyParents(Trip $trip, string $type, string $title, string $message, array $meta = []): void
    {
        $trip->route->students()
            ->where('is_active', true)
            ->with('user')
            ->get()
            ->each(function ($student) use ($trip, $type, $title, $message, $meta) {
                TransportNotification::send(
                    user:    $student->user,
                    type:    $type,
                    title:   $title,
                    message: $message,
                    trip:    $trip,
                    meta:    $meta,
                );
            });
    }

    /**
     * Check whether the bus is within 300 m of any stop on this route.
     * Fires "bus_approaching" when within 500 m and "bus_arrived" when within 80 m.
     * Uses a simple session-like cache key stored on the trip's meta to avoid
     * re-firing the same stop notification every few seconds.
     */
    private function checkStopProximity(Trip $trip, float $lat, float $lng): void
    {
        $alreadyNotified = json_decode($trip->notes ?? '{}', true)['notified_stops'] ?? [];

        $trip->route->stops->each(function ($stop) use ($trip, $lat, $lng, &$alreadyNotified) {
            $dist = $stop->distanceTo($lat, $lng);

            $approachKey = "approach_{$stop->id}";
            $arrivedKey  = "arrived_{$stop->id}";

            // Bus approaching (~500 m)
            if ($dist <= 500 && ! in_array($approachKey, $alreadyNotified, true)) {
                $eta = $dist > 0 ? round($dist / 250 * 60) : 1; // rough ETA in minutes at ~15 km/h

                $this->notifyParentsAtStop($trip, $stop, 'bus_approaching',
                    "🚌 Bus approaching {$stop->name}",
                    "Your child's bus is approximately {$eta} min away from {$stop->name}.",
                    ['stop_name' => $stop->name, 'eta_minutes' => $eta, 'distance_m' => round($dist)]
                );

                $alreadyNotified[] = $approachKey;
            }

            // Bus arrived (<80 m)
            if ($dist <= 80 && ! in_array($arrivedKey, $alreadyNotified, true)) {
                $this->notifyParentsAtStop($trip, $stop, 'bus_arrived',
                    "📍 Bus arrived at {$stop->name}",
                    "The bus has arrived at {$stop->name}. Please ensure your child is ready.",
                    ['stop_name' => $stop->name]
                );

                $alreadyNotified[] = $arrivedKey;
            }
        });

        // Persist the updated notified-stops list back onto the trip
        $notes = json_decode($trip->notes ?? '{}', true);
        $notes['notified_stops'] = $alreadyNotified;
        $trip->updateQuietly(['notes' => json_encode($notes)]);
    }

    /**
     * Send a notification only to parents whose child boards at this specific stop.
     */
    private function notifyParentsAtStop(Trip $trip, $stop, string $type, string $title, string $message, array $meta = []): void
    {
        $stop->students()
            ->where('is_active', true)
            ->with('user')
            ->get()
            ->each(function ($student) use ($trip, $type, $title, $message, $meta) {
                TransportNotification::send(
                    user:    $student->user,
                    type:    $type,
                    title:   $title,
                    message: $message,
                    trip:    $trip,
                    meta:    $meta,
                );
            });
    }
}