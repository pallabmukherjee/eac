<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('unauthorized');
        }

        // Get the authenticated user
        $user = Auth::user();

        // Assign the user's role dynamically
        $role = $user->role;

        // Check if the user's status is 'Active'
        if ($user->status !== 'Active') {
            return redirect()->route('unauthorized');
        }

        // If the user is a SuperAdmin, allow access to all routes
        if ($role === 'SuperAdmin') {
            return $next($request);
        }

        // If the user is an Admin, check the permissions from the UserRole model
        if ($role === 'Admin') {
            $userRole = \App\Models\UserRole::where('user_id', $user->id)->first();
            if ($userRole) {
                // Define permissions
                $permissions = [
                    'payment' => [
                        'superadmin.account.paymentvoucher*',
                        'superadmin.account.beneficiary*',
                    ],
                    'receipt' => [
                        'superadmin.account.receiptvoucher*',
                        'superadmin.account.beneficiary*',
                    ],
                    'contra' => [
                        'superadmin.account.contravoucher*',
                        'superadmin.account.beneficiary*',
                    ],
                    'journal' => [
                        'superadmin.account.journalvoucher.*',
                        'superadmin.account.beneficiary*',
                    ],
                    'leave' => 'superadmin.leave.*',
                    'pension' => 'superadmin.pension.*',
                    'gratuity' => 'superadmin.gratuity.*',
                ];

                // Loop through the permissions and check if any are allowed (1)
                foreach ($permissions as $permission => $routePattern) {
                    if ($userRole->$permission == 1 && $request->routeIs($routePattern)) {
                        return $next($request); // Allow access if permission matches
                    }
                }
            }
            // If no permissions match, redirect to unauthorized page
            return redirect()->route('unauthorized');
        }

        // Default fallback (in case of an unexpected role)
        return redirect()->route('unauthorized');
    }

}
