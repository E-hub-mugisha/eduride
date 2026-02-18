<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;      // parents
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class ParentStudentSeeder extends Seeder
{
    public function run()
    {
        // Fetch all parent users
        $parents = User::where('role', 'parent')->get();

        if ($parents->isEmpty()) {
            $this->command->info('No parent users found. Please seed parent users first.');
            return;
        }

        // Fetch all students
        $students = Student::all();

        if ($students->isEmpty()) {
            $this->command->info('No students found. Please seed students first.');
            return;
        }

        foreach ($students as $student) {
            // Assign 1 or 2 random parents per student
            $assignedParents = $parents->random(rand(1, 2));

            foreach ($assignedParents as $parent) {
                DB::table('parent_student')->insert([
                    'parent_id'  => $parent->id,
                    'student_id' => $student->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Parent-student relationships created successfully!');
    }
}
