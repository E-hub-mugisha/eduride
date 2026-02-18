<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Admins
            [
                'name' => 'Jean-Claude Uwimana',
                'email' => 'admin1@eduride.rw',
                'phone' => '+250788123456',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Alice Mukamana',
                'email' => 'admin2@eduride.rw',
                'phone' => '+250788654321',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ],

            // Drivers
            [
                'name' => 'Emmanuel Nkurunziza',
                'email' => 'driver1@eduride.rw',
                'phone' => '+250785987654',
                'role' => 'driver',
                'password' => Hash::make('driverpass'),
            ],
            [
                'name' => 'Ange Uwase',
                'email' => 'driver2@eduride.rw',
                'phone' => '+250786123987',
                'role' => 'driver',
                'password' => Hash::make('driverpass'),
            ],

            // Parents
            [
                'name' => 'Eric Mugisha',
                'email' => 'parent1@eduride.rw',
                'phone' => '+250788998877',
                'role' => 'parent',
                'password' => Hash::make('parentpass'),
            ],
            [
                'name' => 'Sandrine Uwitonze',
                'email' => 'parent2@eduride.rw',
                'phone' => '+250788112233',
                'role' => 'parent',
                'password' => Hash::make('parentpass'),
            ],
            [
                'name' => 'Olivier Niyonzima',
                'email' => 'parent3@eduride.rw',
                'phone' => '+250788445566',
                'role' => 'parent',
                'password' => Hash::make('parentpass'),
            ],
            [
                'name' => 'Alice Umutoni',
                'email' => 'parent4@eduride.rw',
                'phone' => '+250788556677',
                'role' => 'parent',
                'password' => Hash::make('parentpass'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $this->command->info('Rwandan users seeded successfully!');
    }
}
