<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::withCount(['students', 'notifications'])
            ->when($request->role,   fn ($q) => $q->where('role', $request->role))
            ->when($request->search, fn ($q) => $q->where(function ($q2) use ($request) {
                $q2->where('name',  'like', '%' . $request->search . '%')
                   ->orWhere('email', 'like', '%' . $request->search . '%')
                   ->orWhere('phone', 'like', '%' . $request->search . '%');
            }))
            ->with('driver')
            ->latest()
            ->paginate(15);

        $counts = [
            'all'    => User::count(),
            'admin'  => User::where('role', 'admin')->count(),
            'driver' => User::where('role', 'driver')->count(),
            'parent' => User::where('role', 'parent')->count(),
        ];

        return view('admin.users.index', compact('users', 'counts'));
    }

    public function create()
    {
        $vehicles = Vehicle::active()->whereDoesntHave('driver')->get();

        return view('admin.users.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'role'     => 'required|in:admin,driver,parent',
            'password' => 'required|string|min:8|confirmed',
            // driver-only fields
            'license_number' => 'required_if:role,driver|nullable|string|max:50|unique:drivers,license_number',
            'license_expiry' => 'nullable|date',
            'vehicle_id'     => 'nullable|exists:vehicles,id',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'role'     => $request->role,
                'password' => Hash::make($request->password),
            ]);

            if ($request->role === 'driver') {
                Driver::create([
                    'user_id'        => $user->id,
                    'vehicle_id'     => $request->vehicle_id,
                    'license_number' => $request->license_number,
                    'license_expiry' => $request->license_expiry,
                    'status'         => 'available',
                ]);
            }
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load([
            'driver.vehicle',
            'driver.trips' => fn ($q) => $q->with('route')->latest()->take(5),
            'students.route',
            'students.stop',
            'notifications' => fn ($q) => $q->latest()->take(10),
        ]);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $user->load('driver');
        $vehicles = Vehicle::active()
            ->where(fn ($q) => $q->whereDoesntHave('driver')
                                 ->orWhere('id', $user->driver?->vehicle_id))
            ->get();

        return view('admin.users.edit', compact('user', 'vehicles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone'    => 'nullable|string|max:20',
            'role'     => 'required|in:admin,driver,parent',
            'password' => 'nullable|string|min:8|confirmed',
            'license_number' => [
                'required_if:role,driver',
                'nullable',
                'string',
                'max:50',
                Rule::unique('drivers', 'license_number')->ignore($user->driver?->id),
            ],
            'license_expiry' => 'nullable|date',
            'vehicle_id'     => 'nullable|exists:vehicles,id',
            'status'         => 'nullable|in:available,on_trip,off_duty,suspended',
        ]);

        DB::transaction(function () use ($request, $user) {
            $userData = [
                'name'  => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role'  => $request->role,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            if ($request->role === 'driver') {
                $driverData = [
                    'license_number' => $request->license_number,
                    'license_expiry' => $request->license_expiry,
                    'vehicle_id'     => $request->vehicle_id,
                    'status'         => $request->input('status', 'available'),
                ];
                if ($user->driver) {
                    $user->driver->update($driverData);
                } else {
                    Driver::create(array_merge($driverData, ['user_id' => $user->id]));
                }
            }
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->driver?->activeTrip) {
            return back()->with('error', 'Cannot delete a driver who is currently on a trip.');
        }

        DB::transaction(function () use ($user) {
            $user->driver?->delete();
            $user->students()->delete();
            $user->notifications()->delete();
            $user->delete();
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted.');
    }

    /**
     * Toggle user active status (quick action from index).
     */
    public function toggleStatus(User $user)
    {
        // We use email_verified_at as a proxy for active/inactive
        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $status = 'deactivated';
        } else {
            $user->update(['email_verified_at' => now()]);
            $status = 'activated';
        }

        return back()->with('success', "User {$status} successfully.");
    }

    /**
     * Reset a user's password (admin sets a new one).
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password reset successfully.');
    }
}