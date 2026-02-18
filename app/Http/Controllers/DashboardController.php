<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Trip;
use App\Models\Route as BusRoute;
use App\Models\ParentTripSubscription;

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
                              ->pluck('count','status');

        // Subscriptions per route
        $subscriptionsPerRoute = ParentTripSubscription::with('route')
            ->get()
            ->groupBy(fn($sub) => $sub->route->name ?? 'Unknown')
            ->map->count();

        return view('dashboard', compact(
            'totalUsers',
            'totalVehicles',
            'totalTrips',
            'totalRoutes',
            'totalSubscriptions',
            'tripsByStatus',
            'subscriptionsPerRoute'
        ));
    }
}
