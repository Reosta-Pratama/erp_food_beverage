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
            'email' => 'required|string|email|min:5|max:150',
            'password' => 'required|string|min:6|max:255',
        ], [
            'email.required' => 'Please enter your email address.',
            'email.string' => 'Your email must be a valid text value.',
            'email.email' => 'Please enter a valid email format (e.g., name@example.com).',
            'email.min' => 'Your email must be at least 5 characters long.',
            'email.max' => 'Your email may not be longer than 150 characters.',

            'password.required' => 'Please enter your password.',
            'password.string' => 'Your password must be a valid text value.',
            'password.min' => 'Your password must be at least 6 characters long.',
            'password.max' => 'Your password may not be longer than 255 characters.',
        ]);

        // Attempt to find an active user by email
        $user = User::where('email', $validatedData['email'])
            ->where('is_active', true)
            ->first();

        // If user not found
        if (!$user) {
            return back()
                ->withErrors(['email' => 'No account found with that email address.'])
                ->withInput($request->only('email'));
        }

        // If password does not match
        if (!Hash::check($validatedData['password'], $user->password_hash)) {
            return back()
                ->withErrors(['password' => 'The password you entered is incorrect.'])
                ->withInput($request->only('email'));
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
            ->route('login.show')
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
            ->route('login.show')
            ->with('error', 'Access denied — your role does not have a dashboard assigned.');
    }
}
