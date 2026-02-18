<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Common Rwandan first and last names
        $firstNames = [
            'Eric', 'Jean', 'Alice', 'Emmanuel', 'Josiane', 'Patrick', 'Sandrine', 
            'Fabrice', 'Chantal', 'Claude', 'Diane', 'Jean-Claude', 'Grace', 'Augustin', 'Aline'
        ];

        $lastNames = [
            'Uwimana', 'Niyonkuru', 'Mukamana', 'Bizimana', 'Habimana', 'Uwizeyimana',
            'Rukundo', 'Munyaneza', 'Kamanzi', 'Munyabugingo', 'Ndahiro', 'Uwamahoro', 'Ingabire'
        ];

        // Fetch all routes
        $routes = Route::all();

        if ($routes->isEmpty()) {
            $this->command->info('No routes found. Please seed routes first.');
            return;
        }

        // Create 50 students
        for ($i = 0; $i < 50; $i++) {
            $fullName = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];

            Student::create([
                'full_name' => $fullName,
                'route_id'  => $routes->random()->id, // assign random route
            ]);
        }

        $this->command->info('50 Rwandan students created and assigned to routes!');
    }
}
