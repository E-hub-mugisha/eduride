<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = User::where('role', 'driver')->paginate(10);
        $vehicles = Vehicle::where('driver_id', null)->get();
        return view('drivers.index', compact('drivers', 'vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'phone'=>'required',
            'vehicle_id'=>'nullable|exists:vehicles,id',
            'password'=>'required|min:6'
        ]);

        $driver = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'role'=>'driver',
            'password'=>Hash::make($request->password)
        ]);

        // assign driver to vehicle
        if($request->vehicle_id){
            $vehicle = Vehicle::find($request->vehicle_id);
            $vehicle->driver_id = $driver->id;
            $vehicle->save();
        }

        return redirect()->back()->with('success','Driver added successfully');
    }

    public function update(Request $request, User $driver)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.$driver->id,
            'phone'=>'required',
            'vehicle_id'=>'nullable|exists:vehicles,id'
        ]);

        $driver->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone
        ]);

        // remove old vehicle assignment
        Vehicle::where('driver_id', $driver->id)->update(['driver_id'=>null]);

        // assign to new vehicle
        if($request->vehicle_id){
            $vehicle = Vehicle::find($request->vehicle_id);
            $vehicle->driver_id = $driver->id;
            $vehicle->save();
        }

        return redirect()->back()->with('success','Driver updated successfully');
    }

    public function destroy(User $driver)
    {
        // remove driver from vehicle
        Vehicle::where('driver_id', $driver->id)->update(['driver_id'=>null]);
        $driver->delete();

        return redirect()->back()->with('success','Driver deleted successfully');
    }
}
