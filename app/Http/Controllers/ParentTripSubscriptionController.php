<?php

namespace App\Http\Controllers;

use App\Models\ParentTripSubscription;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Route;
use Illuminate\Support\Facades\Auth;

class ParentTripSubscriptionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'parent') {
            // Parent sees only their subscriptions
            $subscriptions = ParentTripSubscription::with('parent', 'student', 'route')
                ->where('parent_id', $user->id)
                ->get();
        } else {
            // Admin / Manager see all subscriptions
            $subscriptions = ParentTripSubscription::with('parent', 'student', 'route')->get();
        }

        // These are needed for the create/edit modals
        $parents = User::where('role', 'parent')->get();
        $students = Student::all();
        $routes = Route::all();

        return view('subscriptions.index', compact('subscriptions', 'parents', 'students', 'routes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:users,id',
            'route_id' => 'required|exists:routes,id',
            'child_id' => 'nullable|exists:students,id',
            'stop_name' => 'nullable|string|max:255',
        ]);

        ParentTripSubscription::create($request->only(['parent_id', 'route_id', 'child_id', 'stop_name']));

        return redirect()->route('subscriptions.index')->with('success', 'Subscription added.');
    }

    public function update(Request $request, ParentTripSubscription $subscription)
    {
        $request->validate([
            'parent_id' => 'required|exists:users,id',
            'route_id' => 'required|exists:routes,id',
            'child_id' => 'nullable|exists:students,id',
            'stop_name' => 'nullable|string|max:255',
        ]);

        $subscription->update($request->only(['parent_id', 'route_id', 'child_id', 'stop_name']));

        return redirect()->route('subscriptions.index')->with('success', 'Subscription updated.');
    }

    public function destroy(ParentTripSubscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('subscriptions.index')->with('success', 'Subscription deleted.');
    }
}
