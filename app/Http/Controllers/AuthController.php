<?php

namespace App\Http\Controllers;

use App\Models\UserManagement\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    /**
     * Display the login form or redirect authenticated users to their dashboard.
     */
    public function showLogin()
    {
        // If the user is already logged in, redirect them to the appropriate dashboard
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }

        return view('auth.login');
    }

    /**
     * Handle the login request and authenticate the user.
     */
    public function login(Request $request)
    {
        // Validate login form input
        $validatedData = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to find an active user by username
        $user = User::where('username', $validatedData['username'])
            ->where('is_active', true)
            ->first();

        // Verify user existence and password validity
        if (!$user || !Hash::check($validatedData['password'], $user->password_hash)) {
            return back()
                ->withErrors(['username' => 'Invalid username or password.'])
                ->withInput($request->only('username'));
        }

        // Log in the user and optionally remember the session
        Auth::login($user, $request->boolean('remember'));

        // Update last login timestamp
        $user->update(['last_login' => now()]);

        // Regenerate session for security
        $request->session()->regenerate();

        return $this->redirectToDashboard();
    }

    /**
     * Log out the currently authenticated user and invalidate the session.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate and regenerate session for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'You have successfully logged out.');
    }

    /**
     * Redirect the authenticated user to the correct dashboard based on their role.
     */
    private function redirectToDashboard()
    {
        /** @var \App\Models\UserManagement\User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isOperator()) {
            return redirect()->route('operator.dashboard');
        }

        if ($user->isFinanceHr()) {
            return redirect()->route('finance_hr.dashboard');
        }

        // If no matching role route found, log out as a safety measure
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()
            ->route('showLogin')
            ->with('error', 'Access denied — your role does not have a dashboard assigned.');
    }
}
