<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\TransportNotification;
use App\Models\Trip;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $students = $user->students()->with(['route.stops', 'stop'])->get();

        // Find live trips for each student's route
        $activeTrips = [];
        foreach ($students as $student) {
            if ($student->route_id) {
                $trip = Trip::with(['driver.user', 'vehicle', 'route.stops'])
                    ->where('route_id', $student->route_id)
                    ->where('status', 'in_progress')
                    ->first();

                if ($trip) $activeTrips[$student->id] = $trip;
            }
        }

        // Unread count for badge
        $unreadCount = $user->unreadNotifications()->count();

        // Recent notifications (last 5 for home)
        $recentNotifications = $user->notifications()
            ->with('trip.route')
            ->latest()
            ->take(20)
            ->get();

        // Today's scheduled trips for student routes
        $todayTrips = [];
        foreach ($students as $student) {
            if ($student->route_id) {
                $trips = Trip::with(['driver.user', 'vehicle'])
                    ->where('route_id', $student->route_id)
                    ->whereDate('scheduled_at', today())
                    ->orderBy('scheduled_at')
                    ->get();
                if ($trips->isNotEmpty()) {
                    $todayTrips[$student->id] = $trips;
                }
            }
        }

        return view('parent.dashboard', compact(
            'user',
            'students',
            'activeTrips',
            'unreadCount',
            'recentNotifications',
            'todayTrips',
        ));
    }

    /**
     * Live map for a specific trip (parent tracking their child's bus).
     */
    public function track(Trip $trip)
    {
        $user = auth()->user();

        // Ensure this parent has a child on this route
        $hasChild = $user->students()
            ->where('route_id', $trip->route_id)
            ->where('is_active', true)
            ->exists();

        abort_unless($hasChild, 403, 'You do not have a child on this route.');

        $trip->load(['route.stops', 'driver.user', 'vehicle']);

        // Student + their boarding stop
        $student = $user->students()
            ->with('stop')
            ->where('route_id', $trip->route_id)
            ->first();

        $stops = $trip->route->stops->map(fn ($s) => [
            'id'        => $s->id,
            'name'      => $s->name,
            'lat'       => (float) $s->latitude,
            'lng'       => (float) $s->longitude,
            'order'     => $s->order,
            'is_mine'   => $student?->stop_id === $s->id,
        ]);

        return view('parent.track', compact('trip', 'stops', 'student'));
    }

    public function notifications()
    {
        $user = auth()->user();

        $notifications = $user->notifications()
            ->with('trip.route')
            ->latest()
            ->paginate(20);

        // Mark all as read on open
        $user->unreadNotifications()->update(['is_read' => true, 'read_at' => now()]);

        return view('parent.notification', compact('notifications'));
    }

    public function markRead(TransportNotification $n)
    {
        abort_unless($n->user_id === auth()->id(), 403);
        $n->markAsRead();

        return response()->json(['ok' => true]);
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications()
            ->update(['is_read' => true, 'read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }
}