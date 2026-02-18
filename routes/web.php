<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ParentStudentController;
use App\Http\Controllers\ParentTripSubscriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

use App\Mail\TripStartedMail;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('drivers', DriverController::class);
    Route::resource('routes', RouteController::class);
    Route::get('trips', [TripController::class, 'index'])->name('trips.index');
    Route::post('trips/start/{route}', [TripController::class, 'start'])->name('trips.start');
    Route::post('trips/end/{trip}', [TripController::class, 'end'])->name('trips.end');
    Route::post('trips/{trip}/location', [TripController::class, 'updateLocation'])->name('trips.updateLocation');
    Route::get('trips/{trip}/location', [TripController::class, 'getLocation'])->name('trips.getLocation');
    Route::get('trips/{trip}/map', [TripController::class, 'showMap'])->name('trips.showMap');
    Route::get('trips/{trip}/driver-start', [TripController::class, 'driverStart'])->name('trips.driverStart');

    Route::resource('students', StudentController::class)->except(['create', 'edit', 'show']);
    Route::put('students/{student}/parents', [ParentStudentController::class, 'update'])->name('parent_student.update');
    Route::get('subscriptions', [ParentTripSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('subscriptions', [ParentTripSubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::put('subscriptions/{subscription}', [ParentTripSubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::delete('subscriptions/{subscription}', [ParentTripSubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    Route::get('/live-tracking', [App\Http\Controllers\TrackingController::class, 'index'])->name('live.tracking');
    Route::get('/api/trips', [App\Http\Controllers\TrackingController::class, 'tripsApi'])->name('api.trips');
});

Route::get('/test-email', function () {

    $trip = \App\Models\Trip::first();
    $child = \App\Models\Student::first();

    Mail::to('kabosierik@gmail.com')
        ->send(new TripStartedMail($trip, $child));

    return "Email sent!";
});
require __DIR__ . '/auth.php';
