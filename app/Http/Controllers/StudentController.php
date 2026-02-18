<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Route as SchoolRoute;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('route', 'parents')->get();
        $routes = SchoolRoute::all(); // needed for modal select
        $parents = User::where('role', 'parent')->get();
        return view('students.index', compact('students', 'routes','parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'route_id' => 'required|exists:routes,id',
        ]);
        Student::create($request->only(['full_name', 'route_id']));
        return redirect()->route('students.index')->with('success', 'Student added successfully.');
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'route_id' => 'required|exists:routes,id',
        ]);
        $student->update($request->only(['full_name', 'route_id']));
        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
