<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$allowedRoles): Response
    {
        // Redirect unauthenticated users to the login page
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Please log in to continue.');
        }

        /** @var \App\Models\UserManagement\User $user */
        $user = Auth::user();
        $userRoleCode = $user->role->role_code ?? null;

        // Check if the user's role is allowed to access the current route
        if (!in_array($userRoleCode, $allowedRoles, true)) {
            abort(403, 'Access denied — you do not have permission to view this page.');
        }

        // Allow the request to proceed
        return $next($request);
    }
}
