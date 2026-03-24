<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route as TransportRoute;
use App\Models\Stop;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with(['user', 'route', 'stop'])
            ->active()
            ->when($request->route_id, fn($q) => $q->where('route_id', $request->route_id))
            ->when($request->search,   fn($q) => $q->where('full_name', 'like', '%' . $request->search . '%'))
            ->orderBy('full_name')
            ->paginate(15);

        $routes = TransportRoute::active()->orderBy('name')->get();

        return view('admin.students.index', compact('students', 'routes'));
    }

    public function create()
    {
        $routes = TransportRoute::active()->with('stops')->orderBy('name')->get();
        $stops  = Stop::orderBy('name')->get();
        $parents = User::where('role', 'parent')->get();
        return view('admin.students.create', compact('routes', 'stops', 'parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // Parent account
            'parent_id'      => 'required',  // ✅ fixed

            // Student info
            'full_name'      => 'required|string|max:150',
            'student_id'     => 'nullable|string|max:50|unique:students,student_id',
            'grade'          => 'nullable|string|max:20',
            'class_section'  => 'nullable|string|max:20',
            'date_of_birth'  => 'nullable|date|before:today',
            'route_id'       => 'nullable|exists:routes,id',
            'stop_id'        => 'nullable|exists:stops,id',
            'medical_notes'  => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {

            Student::create([
                'user_id'       => $request->parent_id,
                'full_name'     => $request->full_name,
                'student_id'    => $request->student_id,
                'grade'         => $request->grade,
                'class_section' => $request->class_section,
                'date_of_birth' => $request->date_of_birth,
                'route_id'      => $request->route_id,
                'stop_id'       => $request->stop_id,
                'medical_notes' => $request->medical_notes,
                'is_active'     => true,
            ]);
        });

        return redirect()->route('admin.students.index')
            ->with('success', 'Student registered and parent account created.');
    }

    public function show(Student $student)
    {
        $student->load(['user', 'route.stops', 'stop']);

        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $student->load('user');
        $routes = TransportRoute::active()->with('stops')->orderBy('name')->get();
        $stops  = $student->route_id
            ? Stop::where('route_id', $student->route_id)->orderBy('order')->get()
            : collect();

        return view('admin.students.edit', compact('student', 'routes', 'stops'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'parent_name'   => 'required|string|max:150',
            'parent_email'  => 'required|email|unique:users,email,' . $student->user_id,
            'parent_phone'  => 'required|string|max:20',
            'full_name'     => 'required|string|max:150',
            'student_id'    => 'nullable|string|max:50|unique:students,student_id,' . $student->id,
            'grade'         => 'nullable|string|max:20',
            'class_section' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'route_id'      => 'nullable|exists:routes,id',
            'stop_id'       => 'nullable|exists:stops,id',
            'is_active'     => 'boolean',
            'medical_notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $student) {
            $student->user->update([
                'name'  => $request->parent_name,
                'email' => $request->parent_email,
                'phone' => $request->parent_phone,
            ]);

            $student->update([
                'full_name'     => $request->full_name,
                'student_id'    => $request->student_id,
                'grade'         => $request->grade,
                'class_section' => $request->class_section,
                'date_of_birth' => $request->date_of_birth,
                'route_id'      => $request->route_id,
                'stop_id'       => $request->stop_id,
                'is_active'     => $request->boolean('is_active'),
                'medical_notes' => $request->medical_notes,
            ]);
        });

        return redirect()->route('admin.students.index')
            ->with('success', 'Student record updated.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student removed.');
    }
}
