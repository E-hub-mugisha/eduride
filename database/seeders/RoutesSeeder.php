<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Route;
use App\Models\Vehicle;
use App\Models\User; // assuming drivers are users with a 'driver' role

class RoutesSeeder extends Seeder
{
    public function run()
    {
        // Sample Rwandan route data
        $routes = [
            [
                'name' => 'Kigali City Center to Nyamirambo',
                'start_point' => 'Kigali City Center',
                'end_point' => 'Nyamirambo',
                'stops' => 'Kimironko, Remera, Gisozi, Nyakabanda',
                'status' => 'active',
            ],
            [
                'name' => 'Kacyiru to Gikondo',
                'start_point' => 'Kacyiru',
                'end_point' => 'Gikondo',
                'stops' => 'Kimironko, Nyarutarama, Gikondo Market',
                'status' => 'pending',
            ],
            [
                'name' => 'Kanombe to Nyamata',
                'start_point' => 'Kanombe',
                'end_point' => 'Nyamata',
                'stops' => 'Masaka, Bugesera, Nyamata Center',
                'status' => 'active',
            ],
            [
                'name' => 'Remera to Kigali International Airport',
                'start_point' => 'Remera',
                'end_point' => 'Kigali International Airport',
                'stops' => 'Gisozi, Kacyiru, Kanombe',
                'status' => 'active',
            ],
        ];

        // Fetch all vehicles and drivers
        $vehicles = Vehicle::all();
        $drivers = User::where('role', 'driver')->get();

        foreach ($routes as $routeData) {
            $routeData['vehicle_id'] = $vehicles->random()->id ?? null;
            $routeData['driver_id'] = $drivers->random()->id ?? null;
            $routeData['start_time'] = now()->addHours(rand(0, 5))->format('H:i:s');
            $routeData['end_time'] = now()->addHours(rand(6, 12))->format('H:i:s');

            Route::create($routeData);
        }

        $this->command->info('Routes seeded successfully!');
    }
}
