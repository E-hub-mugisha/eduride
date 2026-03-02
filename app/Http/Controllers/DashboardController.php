<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Trip;
use App\Models\Route as BusRoute;
use App\Models\ParentTripSubscription;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalVehicles = Vehicle::count();
        $totalTrips = Trip::count();
        $totalRoutes = BusRoute::count();
        $totalSubscriptions = ParentTripSubscription::count();

        // Trips by status
        $tripsByStatus = Trip::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Subscriptions per route
        $subscriptionsPerRoute = ParentTripSubscription::with('route')
            ->get()
            ->groupBy(fn($sub) => $sub->route->name ?? 'Unknown')
            ->map->count();
        
            $user = Auth::user();

        if ($user->role === 'driver') {
            // Driver sees only their assigned trips
            $trips = Trip::where('driver_id', $user->id)
                ->latest()
                ->paginate(10);
        } else {
            // Admin, Manager, Parent see all trips
            $trips = Trip::latest()->paginate(10);
        }
        return view('dashboard', compact(
            'totalUsers',
            'totalVehicles',
            'totalTrips',
            'totalRoutes',
            'totalSubscriptions',
            'tripsByStatus',
            'subscriptionsPerRoute',
            'trips'
        ));
    }
}
