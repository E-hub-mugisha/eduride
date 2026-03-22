<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Route;
use App\Models\Stop;
use App\Models\Student;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Admin ──────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'IRERERO Admin',
            'email'    => 'admin@irerero.rw',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'phone'    => '+250788000001',
        ]);

        // ── 2. Vehicles ───────────────────────────────────────────
        $vehicles = [
            ['plate_number' => 'RAB 001 A', 'model' => 'Toyota Coaster', 'brand' => 'Toyota', 'color' => 'Yellow', 'capacity' => 30, 'year_manufactured' => 2020, 'status' => 'active'],
            ['plate_number' => 'RAB 002 A', 'model' => 'Isuzu NQR',      'brand' => 'Isuzu',  'color' => 'White',  'capacity' => 45, 'year_manufactured' => 2019, 'status' => 'active'],
            ['plate_number' => 'RAB 003 A', 'model' => 'Toyota Hiace',   'brand' => 'Toyota', 'color' => 'Blue',   'capacity' => 14, 'year_manufactured' => 2021, 'status' => 'active'],
            ['plate_number' => 'RAB 004 A', 'model' => 'Toyota Coaster', 'brand' => 'Toyota', 'color' => 'Yellow', 'capacity' => 30, 'year_manufactured' => 2018, 'status' => 'maintenance'],
        ];

        $vehicleModels = collect($vehicles)->map(fn ($v) => Vehicle::create($v));

        // ── 3. Driver users + Driver records ──────────────────────
        $driversData = [
            ['name' => 'Jean Pierre Nzeyimana', 'email' => 'driver1@irerero.rw', 'phone' => '+250788100001', 'license' => 'RW-DL-20210001', 'vehicle_index' => 0],
            ['name' => 'Emmanuel Habimana',      'email' => 'driver2@irerero.rw', 'phone' => '+250788100002', 'license' => 'RW-DL-20210002', 'vehicle_index' => 1],
            ['name' => 'Théophile Ndayisaba',    'email' => 'driver3@irerero.rw', 'phone' => '+250788100003', 'license' => 'RW-DL-20210003', 'vehicle_index' => 2],
        ];

        $driverModels = collect($driversData)->map(function ($d) use ($vehicleModels) {
            $user = User::create([
                'name'     => $d['name'],
                'email'    => $d['email'],
                'password' => Hash::make('password'),
                'role'     => 'driver',
                'phone'    => $d['phone'],
            ]);

            return Driver::create([
                'user_id'         => $user->id,
                'vehicle_id'      => $vehicleModels[$d['vehicle_index']]->id,
                'license_number'  => $d['license'],
                'license_expiry'  => now()->addYears(3)->format('Y-m-d'),
                'status'          => 'available',
            ]);
        });

        // ── 4. Routes ─────────────────────────────────────────────
        $routesData = [
            [
                'name'                    => 'Route A – Kicukiro',
                'description'             => 'Covers Kicukiro, Niboye, and Kagarama neighbourhoods',
                'type'                    => 'both',
                'morning_departure'       => '06:30:00',
                'afternoon_departure'     => '16:30:00',
                'estimated_duration_min'  => 45,
                'is_active'               => true,
                'stops' => [
                    ['name' => 'Kicukiro Centre',    'landmark' => 'Near Kicukiro market',   'latitude' => -1.9706, 'longitude' => 30.0718, 'order' => 1, 'arrival_offset_min' => 0],
                    ['name' => 'Niboye Junction',    'landmark' => 'Opposite Niboye church', 'latitude' => -1.9775, 'longitude' => 30.0855, 'order' => 2, 'arrival_offset_min' => 8],
                    ['name' => 'Kagarama Gate',      'landmark' => 'Next to Kagarama clinic', 'latitude' => -1.9830, 'longitude' => 30.0930, 'order' => 3, 'arrival_offset_min' => 18],
                    ['name' => 'IRERERO Academy',    'landmark' => 'School main gate',       'latitude' => -1.9900, 'longitude' => 30.1050, 'order' => 4, 'arrival_offset_min' => 35],
                ],
            ],
            [
                'name'                    => 'Route B – Gikondo',
                'description'             => 'Covers Gikondo, Rwandex, and Nyamirambo areas',
                'type'                    => 'both',
                'morning_departure'       => '06:45:00',
                'afternoon_departure'     => '16:45:00',
                'estimated_duration_min'  => 50,
                'is_active'               => true,
                'stops' => [
                    ['name' => 'Gikondo Roundabout', 'landmark' => 'Next to Rwandex factory', 'latitude' => -1.9630, 'longitude' => 30.0510, 'order' => 1, 'arrival_offset_min' => 0],
                    ['name' => 'Nyamirambo Centre',  'landmark' => 'Opposite Total station',  'latitude' => -1.9710, 'longitude' => 30.0380, 'order' => 2, 'arrival_offset_min' => 12],
                    ['name' => 'Muhima Junction',    'landmark' => 'Near Muhima hospital',    'latitude' => -1.9540, 'longitude' => 30.0590, 'order' => 3, 'arrival_offset_min' => 28],
                    ['name' => 'IRERERO Academy',    'landmark' => 'School main gate',        'latitude' => -1.9900, 'longitude' => 30.1050, 'order' => 4, 'arrival_offset_min' => 45],
                ],
            ],
            [
                'name'                    => 'Route C – Remera',
                'description'             => 'Covers Remera, Kibagabaga, and Airport road',
                'type'                    => 'both',
                'morning_departure'       => '06:15:00',
                'afternoon_departure'     => '16:15:00',
                'estimated_duration_min'  => 40,
                'is_active'               => true,
                'stops' => [
                    ['name' => 'Remera Taxi Park',  'landmark' => 'Opposite UTC Remera',       'latitude' => -1.9402, 'longitude' => 30.1105, 'order' => 1, 'arrival_offset_min' => 0],
                    ['name' => 'Kibagabaga Market', 'landmark' => 'Near Kibagabaga hospital',  'latitude' => -1.9321, 'longitude' => 30.1195, 'order' => 2, 'arrival_offset_min' => 10],
                    ['name' => 'Airport Road',      'landmark' => 'KBC roundabout',            'latitude' => -1.9500, 'longitude' => 30.1290, 'order' => 3, 'arrival_offset_min' => 22],
                    ['name' => 'IRERERO Academy',   'landmark' => 'School main gate',          'latitude' => -1.9900, 'longitude' => 30.1050, 'order' => 4, 'arrival_offset_min' => 38],
                ],
            ],
        ];

        $routeModels = [];
        $stopModels  = [];

        foreach ($routesData as $rd) {
            $stopsRaw = $rd['stops'];
            unset($rd['stops']);

            $route = Route::create($rd);
            $routeModels[] = $route;

            $routeStops = [];
            foreach ($stopsRaw as $sd) {
                $routeStops[] = Stop::create(array_merge($sd, ['route_id' => $route->id]));
            }
            $stopModels[] = $routeStops;
        }

        // ── 5. Parent users + Students ────────────────────────────
        $parentsData = [
            ['name' => 'Alice Mukamana',   'email' => 'parent1@example.rw', 'phone' => '+250788200001', 'student_name' => 'Kevin Mukamana',   'grade' => 'P5', 'route_i' => 0, 'stop_i' => 1],
            ['name' => 'Robert Habimana',  'email' => 'parent2@example.rw', 'phone' => '+250788200002', 'student_name' => 'Grace Habimana',   'grade' => 'S2', 'route_i' => 0, 'stop_i' => 2],
            ['name' => 'Marie Uwase',      'email' => 'parent3@example.rw', 'phone' => '+250788200003', 'student_name' => 'Luc Uwase',        'grade' => 'P3', 'route_i' => 1, 'stop_i' => 0],
            ['name' => 'Joseph Nzeyimana', 'email' => 'parent4@example.rw', 'phone' => '+250788200004', 'student_name' => 'Claire Nzeyimana', 'grade' => 'S4', 'route_i' => 1, 'stop_i' => 1],
            ['name' => 'Diane Ingabire',   'email' => 'parent5@example.rw', 'phone' => '+250788200005', 'student_name' => 'Ethan Ingabire',   'grade' => 'P6', 'route_i' => 2, 'stop_i' => 0],
            ['name' => 'Pascal Nsabimana', 'email' => 'parent6@example.rw', 'phone' => '+250788200006', 'student_name' => 'Ines Nsabimana',   'grade' => 'S1', 'route_i' => 2, 'stop_i' => 1],
        ];

        foreach ($parentsData as $pd) {
            $parent = User::create([
                'name'     => $pd['name'],
                'email'    => $pd['email'],
                'password' => Hash::make('password'),
                'role'     => 'parent',
                'phone'    => $pd['phone'],
            ]);

            Student::create([
                'user_id'      => $parent->id,
                'route_id'     => $routeModels[$pd['route_i']]->id,
                'stop_id'      => $stopModels[$pd['route_i']][$pd['stop_i']]->id,
                'full_name'    => $pd['student_name'],
                'grade'        => $pd['grade'],
                'class_section' => 'A',
                'is_active'    => true,
            ]);
        }

        // ── 6. Sample completed trip (Route A, Driver 1) ──────────
        Trip::create([
            'route_id'     => $routeModels[0]->id,
            'driver_id'    => $driverModels[0]->id,
            'vehicle_id'   => $vehicleModels[0]->id,
            'type'         => 'morning',
            'status'       => 'completed',
            'scheduled_at' => now()->subDay()->setTime(6, 30),
            'started_at'   => now()->subDay()->setTime(6, 32),
            'ended_at'     => now()->subDay()->setTime(7, 18),
        ]);

        $this->command->info('✅  EDURIDE seed completed.');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',    'admin@irerero.rw',    'password'],
                ['Driver 1', 'driver1@irerero.rw',  'password'],
                ['Driver 2', 'driver2@irerero.rw',  'password'],
                ['Driver 3', 'driver3@irerero.rw',  'password'],
                ['Parent 1', 'parent1@example.rw',  'password'],
                ['Parent 2', 'parent2@example.rw',  'password'],
            ]
        );
    }
}