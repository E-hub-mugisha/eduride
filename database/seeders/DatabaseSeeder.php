<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,                  // parents, drivers
            VehicleSeeder::class,               // vehicles
            RoutesSeeder::class,                 // routes with stops
            StudentsTableSeeder::class,               // students assigned to routes
            ParentStudentSeeder::class,         // parent-child relationships
            ParentTripSubscriptionSeeder::class // parent subscriptions with random stops
        ]);
    }
}
