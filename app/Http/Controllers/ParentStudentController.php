<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class ParentStudentController extends Controller
{
    public function update(Request $request, Student $student){
        $request->validate([
            'parents'=>'nullable|array'
        ]);

        // Sync parents
        $student->parents()->sync($request->parents ?? []);

        return redirect()->route('students.index')
            ->with('success',"Parents updated for {$student->full_name}.");
    }
}
