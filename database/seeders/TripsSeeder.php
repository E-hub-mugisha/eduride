<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Route;

class TripSeeder extends Seeder
{
    public function run()
    {
        // Fetch all vehicles, drivers, and routes
        $vehicles = Vehicle::all();
        $drivers = User::where('role', 'driver')->get();
        $routes = Route::all();

        // Number of trips to create
        $tripCount = 20;

        for ($i = 0; $i < $tripCount; $i++) {
            $vehicle = $vehicles->random();
            $driver = $drivers->random();
            $route = $routes->random();

            Trip::create([
                'vehicle_id' => $vehicle->id,
                'driver_id' => $driver->id,
                'route_id' => $route->id,
                'status' => ['pending', 'in_progress', 'completed'][array_rand(['pending', 'in_progress', 'completed'])],
                // Random coordinates near Kigali (for example)
                'current_lat' => rand(-12000000, -11000000) / 1000000, 
                'current_lng' => rand(3000000, 3100000) / 1000000, 
            ]);
        }

        $this->command->info("{$tripCount} trips seeded successfully!");
    }
}
