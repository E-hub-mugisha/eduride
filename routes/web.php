<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Driver\DriverTripController;
use Illuminate\Support\Facades\Route;

// ── Public landing page ───────────────────────────────────────────────────────
Route::get('/', fn() => view('welcome'))->name('home');

// ── Auth (Laravel Breeze / generated) ────────────────────────────────────────
require __DIR__ . '/auth.php';

// ── Post-login redirect based on role ────────────────────────────────────────
Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin'  => redirect()->route('admin.dashboard'),
        'driver' => redirect()->route('driver.dashboard'),
        'parent' => redirect()->route('parent.dashboard'),
        default  => redirect()->route('home'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');


// ════════════════════════════════════════════════════════════════════════════
//  ADMIN
// ════════════════════════════════════════════════════════════════════════════
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Vehicles
        Route::resource('vehicles', VehicleController::class);

        // Drivers
        Route::resource('drivers', DriverController::class);

        // Routes & Stops
        Route::resource('routes', RouteController::class);
        Route::prefix('routes/{route}/stops')->name('routes.stops.')->group(function () {
            Route::post('/',            [RouteController::class, 'storeStop'])->name('store');
            Route::put('{stop}',        [RouteController::class, 'updateStop'])->name('update');
            Route::delete('{stop}',     [RouteController::class, 'destroyStop'])->name('destroy');
            Route::post('reorder',      [RouteController::class, 'reorderStops'])->name('reorder');
        });

        Route::get('routes/{route}/stops-json', [RouteController::class, 'stopsJson'])->name('routes.stops.json');
        // Students
        Route::resource('students', StudentController::class);

        // Trips (admin view only — drivers control start/end)
        Route::resource('trips', TripController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
        Route::get('trips/{trip}/track', [TripController::class, 'track'])->name('trips.track');

        // Live map — all active trips
        Route::get('map', [DashboardController::class, 'liveMap'])->name('map');

        // Live position polling endpoint (called by map JS every 5s)
        Route::get('trips/{trip}/position', [TripController::class, 'position'])->name('trips.position');

        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-status',  [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    });


// ════════════════════════════════════════════════════════════════════════════
//  DRIVER
// ════════════════════════════════════════════════════════════════════════════
Route::prefix('driver')
    ->name('driver.')
    ->middleware(['auth', 'verified', 'role:driver'])
    ->group(function () {

        Route::get('/',                              [DriverTripController::class, 'dashboard'])->name('dashboard');
        Route::get('trip/{trip}',                    [DriverTripController::class, 'show'])->name('trip.show');
        Route::post('trip/{trip}/start',             [DriverTripController::class, 'start'])->name('trip.start');
        Route::post('trip/{trip}/end',               [DriverTripController::class, 'end'])->name('trip.end');
        Route::post('trip/{trip}/location',          [DriverTripController::class, 'location'])->name('trip.location');
        Route::post('trip/{trip}/sos',               [DriverTripController::class, 'sos'])->name('trip.sos');

        Route::get('trips/{trip}/position', function (\App\Models\Trip $trip) {
            return response()->json([
                'status' => $trip->status,
                'lat'    => $trip->current_latitude  ? (float) $trip->current_latitude  : null,
                'lng'    => $trip->current_longitude ? (float) $trip->current_longitude : null,
            ]);
        })->name('trip.position');
    });


// ════════════════════════════════════════════════════════════════════════════
//  PARENT
// ════════════════════════════════════════════════════════════════════════════
Route::prefix('parent')
    ->name('parent.')
    ->middleware(['auth', 'verified', 'role:parent'])
    ->group(function () {

        Route::get('/',                              [\App\Http\Controllers\Parent\DashboardController::class, 'index'])->name('dashboard');
        Route::get('track/{trip}',                   [\App\Http\Controllers\Parent\DashboardController::class, 'track'])->name('track');
        Route::get('notifications',                  [\App\Http\Controllers\Parent\DashboardController::class, 'notifications'])->name('notifications');
        Route::post('notifications/{n}/read',        [\App\Http\Controllers\Parent\DashboardController::class, 'markRead'])->name('notifications.read');
        Route::post('notifications/read-all',        [\App\Http\Controllers\Parent\DashboardController::class, 'markAllRead'])->name('notifications.readAll');
    });
