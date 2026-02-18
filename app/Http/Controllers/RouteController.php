<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::latest()->paginate(10);
        $vehicles = Vehicle::where('status','active')->get();
        $drivers = User::where('role','driver')->get();
        return view('routes.index', compact('routes','vehicles','drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'start_point'=>'required',
            'end_point'=>'required',
            'vehicle_id'=>'nullable|exists:vehicles,id',
            'driver_id'=>'nullable|exists:users,id',
            'start_time'=>'nullable',
            'end_time'=>'nullable',
            'status'=>'required|in:pending,active,completed'
        ]);

        Route::create($request->only([
            'name','start_point','end_point','stops','vehicle_id','driver_id','start_time','end_time','status'
        ]));

        return redirect()->back()->with('success','Route created successfully');
    }

    public function update(Request $request, Route $route)
    {
        $request->validate([
            'name'=>'required',
            'start_point'=>'required',
            'end_point'=>'required',
            'vehicle_id'=>'nullable|exists:vehicles,id',
            'driver_id'=>'nullable|exists:users,id',
            'start_time'=>'nullable',
            'end_time'=>'nullable',
            'status'=>'required|in:pending,active,completed'
        ]);

        $route->update($request->only([
            'name','start_point','end_point','stops','vehicle_id','driver_id','start_time','end_time','status'
        ]));

        return redirect()->back()->with('success','Route updated successfully');
    }

    public function destroy(Route $route)
    {
        $route->delete();
        return redirect()->back()->with('success','Route deleted successfully');
    }
}
