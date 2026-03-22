<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::with(['user', 'vehicle'])
            ->withCount('trips')
            ->latest()
            ->paginate(12);

        return view('admin.drivers.index', compact('drivers'));
    }

    public function create()
    {
        // Only vehicles without an assigned driver
        $vehicles = Vehicle::active()
            ->whereDoesntHave('driver')
            ->get();

        return view('admin.drivers.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:150',
            'email'          => 'required|email|unique:users,email',
            'phone'          => 'required|string|max:20',
            'password'       => 'required|string|min:8|confirmed',
            'license_number' => 'required|string|max:50|unique:drivers,license_number',
            'license_expiry' => 'nullable|date|after:today',
            'vehicle_id'     => 'nullable|exists:vehicles,id',
            'status'         => 'required|in:available,off_duty,suspended',
            'notes'          => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'role'     => 'driver',
            ]);

            Driver::create([
                'user_id'        => $user->id,
                'vehicle_id'     => $request->vehicle_id,
                'license_number' => $request->license_number,
                'license_expiry' => $request->license_expiry,
                'status'         => $request->status,
                'notes'          => $request->notes,
            ]);
        });

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver registered successfully.');
    }

    public function show(Driver $driver)
    {
        $driver->load(['user', 'vehicle', 'trips' => fn ($q) => $q->with('route')->latest()->take(10)]);

        return view('admin.drivers.show', compact('driver'));
    }

    public function edit(Driver $driver)
    {
        $driver->load('user');

        $vehicles = Vehicle::active()
            ->where(fn ($q) => $q->whereDoesntHave('driver')
                                 ->orWhere('id', $driver->vehicle_id))
            ->get();

        return view('admin.drivers.edit', compact('driver', 'vehicles'));
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'name'           => 'required|string|max:150',
            'email'          => 'required|email|unique:users,email,' . $driver->user_id,
            'phone'          => 'required|string|max:20',
            'password'       => 'nullable|string|min:8|confirmed',
            'license_number' => 'required|string|max:50|unique:drivers,license_number,' . $driver->id,
            'license_expiry' => 'nullable|date',
            'vehicle_id'     => 'nullable|exists:vehicles,id',
            'status'         => 'required|in:available,on_trip,off_duty,suspended',
            'notes'          => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $driver) {
            $userData = [
                'name'  => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $driver->user->update($userData);

            $driver->update([
                'vehicle_id'     => $request->vehicle_id,
                'license_number' => $request->license_number,
                'license_expiry' => $request->license_expiry,
                'status'         => $request->status,
                'notes'          => $request->notes,
            ]);
        });

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver updated successfully.');
    }

    public function destroy(Driver $driver)
    {
        if ($driver->activeTrip) {
            return back()->with('error', 'Cannot remove a driver who is currently on a trip.');
        }

        DB::transaction(function () use ($driver) {
            $user = $driver->user;
            $driver->delete();
            $user->delete();
        });

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver removed.');
    }
}