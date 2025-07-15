<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserRole;

class UserRoleController extends Controller {
    public function index() {
        $users = UserRole::orderBy('created_at', 'asc')->get();
        return view("layouts.pages.user.role", compact('users'));
    }

    public function updateField(Request $request) {
        // Validate the incoming request
        $request->validate([
            'user_id' => 'required|exists:user_roles,id',
            'field' => 'required|in:payment,receipt,contra,journal,leave,pension,gratuity',
            'value' => 'required|boolean',
        ]);

        // Find or create the user's role record (UserRole)
        $userRole = UserRole::firstOrCreate(
            ['id' => $request->input('user_id')], // Find by user_id, or create new if not found
            [
                'payment' => false,
                'receipt' => false,
                'contra' => false,
                'journal' => false,
                'leave' => false,
                'pension' => false,
                'gratuity' => false,
            ]
        );

        // Dynamically update the field
        $userRole->{$request->input('field')} = $request->input('value');
        $userRole->save();  // Save the updated UserRole record

        return response()->json(['success' => true]);  // Success response
    }




}
