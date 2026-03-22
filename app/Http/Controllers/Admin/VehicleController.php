<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::withCount('trips')
            ->with('driver.user')
            ->latest()
            ->paginate(12);

        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('admin.vehicles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plate_number'     => 'required|string|max:20|unique:vehicles',
            'model'            => 'required|string|max:100',
            'brand'            => 'nullable|string|max:100',
            'color'            => 'nullable|string|max:50',
            'capacity'         => 'required|integer|min:1|max:100',
            'year_manufactured'=> 'nullable|integer|min:1990|max:' . date('Y'),
            'status'           => 'required|in:active,maintenance,inactive',
            'photo'            => 'nullable|image|max:2048',
            'notes'            => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('vehicles', 'public');
        }

        Vehicle::create($data);

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle added successfully.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['driver.user', 'trips' => fn ($q) => $q->latest()->take(10)]);

        return view('admin.vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'plate_number'     => 'required|string|max:20|unique:vehicles,plate_number,' . $vehicle->id,
            'model'            => 'required|string|max:100',
            'brand'            => 'nullable|string|max:100',
            'color'            => 'nullable|string|max:50',
            'capacity'         => 'required|integer|min:1|max:100',
            'year_manufactured'=> 'nullable|integer|min:1990|max:' . date('Y'),
            'status'           => 'required|in:active,maintenance,inactive',
            'photo'            => 'nullable|image|max:2048',
            'notes'            => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('photo')) {
            if ($vehicle->photo) Storage::disk('public')->delete($vehicle->photo);
            $data['photo'] = $request->file('photo')->store('vehicles', 'public');
        }

        $vehicle->update($data);

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->activeTrip) {
            return back()->with('error', 'Cannot delete a vehicle that is currently on a trip.');
        }

        if ($vehicle->photo) Storage::disk('public')->delete($vehicle->photo);

        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle removed.');
    }
}