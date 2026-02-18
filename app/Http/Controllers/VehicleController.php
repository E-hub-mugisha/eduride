<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::latest()->paginate(10);
        return view('vehicles.index', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|unique:vehicles,plate_number',
            'model' => 'required',
            'capacity' => 'required|integer|min:1',
            'status' => 'required'
        ]);

        Vehicle::create($request->all());

        return redirect()->back()->with('success', 'Vehicle added successfully');
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'plate_number' => 'required|unique:vehicles,plate_number,' . $vehicle->id,
            'model' => 'required',
            'capacity' => 'required|integer|min:1',
            'status' => 'required'
        ]);

        $vehicle->update($request->all());

        return redirect()->back()->with('success', 'Vehicle updated successfully');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->back()->with('success', 'Vehicle deleted successfully');
    }
}
