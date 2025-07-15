<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function index() {
        $users = User::where('role', '!=', 'SuperAdmin')->orderBy('created_at', 'desc')->get();
        return view("layouts.pages.user.add", compact('users'));
    }

    public function createUser(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Create a new user in the database
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'show_password' => $validated['password'],
            'role' => 'unknown',
            'status' => 'Blocked',
            'password' => Hash::make($validated['password']),
        ]);

        // Redirect back with success message
        return redirect()->route('superadmin.user.index')->with('success', 'User created successfully!');
    }

    public function updateStatus(Request $request) {
        // Validate the incoming request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:Blocked,Active',
        ]);

        // Find the user by ID
        $user = User::find($request->input('user_id'));

        // Update the user's status
        $user->status = $request->input('status');

        if ($user->status === 'Blocked') {
            // Set role to 'Unknown' when blocked
            $user->role = 'unknown';

            // Delete the UserRole data if the user is blocked
            $userRole = UserRole::where('user_id', $user->id)->first();
            if ($userRole) {
                $userRole->delete(); // Delete UserRole when blocked
            }
        } elseif ($user->status === 'Active') {
            // Set role to 'Admin' when active
            $user->role = 'Admin';

            // Create or update the UserRole data when active
            $userRole = UserRole::updateOrCreate(
                ['user_id' => $user->id],  // Check if UserRole exists for this user_id
                [
                    'user_id'  => $user->id,
                    'payment'  => false,
                    'receipt'  => false,
                    'contra'   => false,
                    'journal'  => false,
                    'leave'    => false,
                    'pension'  => false,
                    'gratuity' => false
                ]
            );
        }

        // Save the user's status and role
        $user->save();

        // Return a success response
        return response()->json(['success' => true]);
    }

}
