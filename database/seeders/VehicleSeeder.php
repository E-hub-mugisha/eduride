<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            [
                'plate_number' => 'RAB 123A',
                'model' => 'Toyota Hiace',
                'capacity' => 15,
                'status' => 'active',
            ],
            [
                'plate_number' => 'RAC 456B',
                'model' => 'Toyota Land Cruiser',
                'capacity' => 7,
                'status' => 'active',
            ],
            [
                'plate_number' => 'RAD 789C',
                'model' => 'Nissan Urvan',
                'capacity' => 14,
                'status' => 'active',
            ],
            [
                'plate_number' => 'RAE 234D',
                'model' => 'Mitsubishi L300',
                'capacity' => 12,
                'status' => 'active',
            ],
            [
                'plate_number' => 'RAF 567E',
                'model' => 'Toyota Prado',
                'capacity' => 7,
                'status' => 'inactive',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::create($vehicle);
        }

        $this->command->info('Vehicles seeded successfully!');
    }
}
