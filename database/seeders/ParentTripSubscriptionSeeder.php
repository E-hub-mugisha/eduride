<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;       // parents
use App\Models\Student;
use App\Models\Route;
use Illuminate\Support\Facades\DB;

class ParentTripSubscriptionSeeder extends Seeder
{
    public function run()
    {
        $parents = User::where('role', 'parent')->get();
        $students = Student::all();

        if ($parents->isEmpty() || $students->isEmpty()) {
            $this->command->info('Seed parents and students first.');
            return;
        }

        // Example realistic Rwandan route stops
        $realisticStops = [
            'Kigali City Center',
            'Nyamirambo',
            'Kimironko',
            'Remera',
            'Gikondo',
            'Nyarutarama',
            'Kacyiru',
            'Kicukiro',
            'Kanombe',
            'Gisozi',
            'Nyabugogo',
        ];

        // First, make sure each route has stops if empty
        $routes = DB::table('routes')->get();
        foreach ($routes as $route) {
            if (empty($route->stops)) {
                // pick 3–5 random stops for the route
                $routeStops = collect($realisticStops)->shuffle()->take(rand(3,5))->implode(', ');
                DB::table('routes')->where('id', $route->id)->update(['stops' => $routeStops]);
            }
        }

        foreach ($parents as $parent) {
            // Each parent subscribes to 1–2 random routes
            $subscribedRoutes = $routes->shuffle()->take(rand(1, 2));

            foreach ($subscribedRoutes as $route) {
                // Assign child if the parent has children on this route
                $child = $students
                    ->where('route_id', $route->id)
                    ->whereIn('id', DB::table('parent_student')
                        ->where('parent_id', $parent->id)
                        ->pluck('student_id'))
                    ->random(1)
                    ->first();

                // Pick a random stop from route stops
                $stopName = null;
                if (!empty($route->stops)) {
                    $stopsArray = array_map('trim', explode(',', $route->stops));
                    if (!empty($stopsArray)) {
                        $stopName = $stopsArray[array_rand($stopsArray)];
                    }
                }

                DB::table('parent_trip_subscriptions')->insert([
                    'parent_id'  => $parent->id,
                    'route_id'   => $route->id,
                    'child_id'   => $child ? $child->id : null,
                    'stop_name'  => $stopName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Parent trip subscriptions with realistic Rwandan stops created successfully!');
    }
}
