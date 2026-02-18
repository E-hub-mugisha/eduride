<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\User;

class VehicleDriverSeeder extends Seeder
{
    public function run()
    {
        // Fetch all drivers
        $drivers = User::where('role', 'driver')->get();

        // Assign a random driver to each vehicle
        Vehicle::all()->each(function ($vehicle) use ($drivers) {
            // 70% chance to assign a driver
            if ($drivers->count() && rand(0, 100) < 70) {
                $vehicle->driver_id = $drivers->random()->id;
                $vehicle->save();
            }
        });

        $this->command->info('Drivers assigned to vehicles successfully!');
    }
}
