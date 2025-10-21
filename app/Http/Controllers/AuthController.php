<?php

namespace App\Http\Controllers;

use App\Models\UserManagement\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)
                    ->where('is_active', true)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            return back()
                ->withErrors(['username' => 'Username atau password salah'])
                ->withInput($request->only('username'));
        }

        // Login user
        Auth::login($user, $request->filled('remember'));

        // Update last login
        $user->update(['last_login' => now()]);

        // Regenerate session
        $request->session()->regenerate();

        return $this->redirectToDashboard();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout');
    }

    private function redirectToDashboard()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isOperator()) {
            return redirect()->route('operator.dashboard');
        } elseif ($user->isFinanceHr()) {
            return redirect()->route('finance_hr.dashboard');
        }

        return redirect()->route('dashboard');
    }
}
