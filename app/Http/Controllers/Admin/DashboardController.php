<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Route;
use App\Models\Student;
use App\Models\TransportNotification;
use App\Models\Trip;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_vehicles'  => Vehicle::count(),
            'active_vehicles' => Vehicle::where('status', 'active')->count(),
            'total_drivers'   => Driver::count(),
            'on_trip_drivers' => Driver::where('status', 'on_trip')->count(),
            'total_routes'    => Route::where('is_active', true)->count(),
            'total_students'  => Student::where('is_active', true)->count(),
            'trips_today'     => Trip::whereDate('scheduled_at', today())->count(),
            'active_trips'    => Trip::where('status', 'in_progress')->count(),
        ];

        $activeTrips = Trip::with(['route', 'driver.user', 'vehicle'])
            ->where('status', 'in_progress')
            ->get();

        $todayTrips = Trip::with(['route', 'driver.user', 'vehicle'])
            ->whereDate('scheduled_at', today())
            ->orderBy('scheduled_at')
            ->get();

        $recentNotifications = TransportNotification::with('user')
            ->latest()
            ->take(6)
            ->get();

        // ── Trips last 7 days (line chart) ───────────────────────────────
        $tripsByDayRaw = Trip::selectRaw('DATE(scheduled_at) as date, COUNT(*) as total')
            ->whereDate('scheduled_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $chartLabels = [];
        $chartTrips  = [];
        for ($i = 6; $i >= 0; $i--) {
            $date          = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('D');
            $chartTrips[]  = (int) ($tripsByDayRaw[$date] ?? 0);
        }

        // ── Trips by status (donut) ───────────────────────────────────────
        $tripsByStatus = Trip::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusLabels = ['Scheduled', 'In Progress', 'Completed', 'Cancelled', 'Delayed'];
        $statusKeys   = ['scheduled', 'in_progress', 'completed', 'cancelled', 'delayed'];
        $statusData   = array_map(fn($k) => (int)($tripsByStatus[$k] ?? 0), $statusKeys);

        // ── Trips by route — last 30 days (horizontal bar) ───────────────
        $tripsByRoute = Trip::selectRaw('route_id, COUNT(*) as total')
            ->where('status', 'completed')
            ->whereDate('scheduled_at', '>=', now()->subDays(29))
            ->groupBy('route_id')
            ->orderByDesc('total')
            ->with('route:id,name')
            ->get();

        $routeLabels = [];
        $routeData   = [];
        foreach ($tripsByRoute as $r) {
            $routeLabels[] = $r->route?->name ?? 'Unknown';
            $routeData[]   = (int) $r->total;
        }

        // ── Monthly completed trips — last 6 months (area chart) ─────────
        $monthlyRaw = Trip::selectRaw("DATE_FORMAT(scheduled_at, '%Y-%m') as month, COUNT(*) as total")
            ->where('status', 'completed')
            ->whereDate('scheduled_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $monthLabels = [];
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $key           = now()->subMonths($i)->format('Y-m');
            $monthLabels[] = now()->subMonths($i)->format('M Y');
            $monthlyData[] = (int) ($monthlyRaw[$key] ?? 0);
        }

        // ── Top 5 drivers by trips — last 30 days ────────────────────────
        $driverActivity = Trip::selectRaw('driver_id, COUNT(*) as total')
            ->where('status', 'completed')
            ->whereDate('scheduled_at', '>=', now()->subDays(29))
            ->groupBy('driver_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('driver.user:id,name')
            ->get();

        $driverLabels = [];
        $driverData   = [];
        foreach ($driverActivity as $d) {
            $driverLabels[] = $d->driver?->name ?? 'Unknown';
            $driverData[]   = (int) $d->total;
        }

        // ── All-time totals ───────────────────────────────────────────────
        $totalTripsAllTime   = Trip::where('status', 'completed')->count();
        $totalTripsThisMonth = Trip::where('status', 'completed')
            ->whereMonth('scheduled_at', now()->month)
            ->whereYear('scheduled_at', now()->year)
            ->count();

        return view('admin.dashboard.index', compact(
            'stats', 'activeTrips', 'todayTrips', 'recentNotifications',
            'chartLabels', 'chartTrips',
            'statusLabels', 'statusData',
            'routeLabels', 'routeData',
            'monthLabels', 'monthlyData',
            'driverLabels', 'driverData',
            'totalTripsAllTime', 'totalTripsThisMonth',
        ));
    }

    public function liveMap()
    {
        $activeTrips = Trip::with(['route.stops', 'driver.user', 'vehicle'])
            ->where('status', 'in_progress')
            ->get();

        $routes = Route::with('stops')->where('is_active', true)->get();

        /*
         * Pre-serialise everything into plain arrays here in PHP.
         * Never use closures or array literals inside @json() in Blade —
         * the template engine misparses the nested brackets.
         */
        $tripsData = [];
        foreach ($activeTrips as $t) {
            $stops = [];
            foreach ($t->route->stops as $s) {
                $stops[] = [
                    'name'  => $s->name,
                    'lat'   => (float) $s->latitude,
                    'lng'   => (float) $s->longitude,
                    'order' => $s->order,
                ];
            }

            $tripsData[] = [
                'id'        => $t->id,
                'name'      => $t->route->name,
                'driver'    => $t->driver->name,
                'plate'     => $t->vehicle->plate_number,
                'lat'       => $t->current_latitude  ? (float) $t->current_latitude  : null,
                'lng'       => $t->current_longitude ? (float) $t->current_longitude : null,
                'speed'     => $t->current_speed ? (int) round($t->current_speed) : 0,
                'stale'     => (bool) $t->is_location_stale,
                'track_url' => route('admin.trips.track', $t->id),
                'stops'     => $stops,
            ];
        }

        $routesData = [];
        foreach ($routes as $r) {
            $rStops = [];
            foreach ($r->stops as $s) {
                $rStops[] = [
                    'lat'  => (float) $s->latitude,
                    'lng'  => (float) $s->longitude,
                    'name' => $s->name,
                ];
            }
            $routesData[] = [
                'name'  => $r->name,
                'stops' => $rStops,
            ];
        }

        return view('admin.dashboard.live-map', compact(
            'activeTrips', 'routes', 'tripsData', 'routesData',
        ));
    }
}