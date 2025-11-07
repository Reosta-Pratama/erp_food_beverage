<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserManagement\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    //
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.role_id')
            ->leftJoin('employees', 'users.employee_id', '=', 'employees.employee_id')
            ->select(
                'users.user_id',
                'users.username',
                'users.email',
                'users.full_name',
                'users.phone',
                'users.is_active',
                'users.last_login',
                'users.created_at',
                'roles.role_id',
                'roles.role_name',
                'roles.role_code',
                DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) as employee_name"),
                'employees.employee_code'
            );
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('users.username', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('users.full_name', 'like', "%{$search}%");
            });
        }
        
        // Filter by role
        if ($request->filled('role')) {
            $query->where('roles.role_code', $request->role);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('users.is_active', $request->status === 'active' ? 1 : 0);
        }
        
        $users = $query->orderByDesc('users.created_at')
            ->paginate(20);
        
        // Get roles for filter (keep using model for small datasets)
        $roles = Role::select('role_id', 'role_name', 'role_code')->get();
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::select('role_id', 'role_name', 'role_code')->get();
        
        // Optimized: Get employees without users using subquery
        $employees = DB::table('employees')
            ->leftJoin('users', 'employees.employee_id', '=', 'users.employee_id')
            ->whereNull('users.user_id')
            ->select(
                'employees.employee_id',
                'employees.employee_code',
                DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) as full_name")
            )
            ->orderBy('employees.employee_code')
            ->get();
        
        return view('admin.users.create', compact('roles', 'employees'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:100', 'unique:users,username', 'alpha_dash'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'full_name' => ['required', 'string', 'max:200'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role_id' => ['required', 'exists:roles,role_id'],
            'employee_id' => ['nullable', 'exists:employees,employee_id', 'unique:users,employee_id'],
            'is_active' => ['boolean'],
        ]);
        
        // Use DB transaction for safety
        DB::beginTransaction();
        try {
            $userId = DB::table('users')->insertGetId([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password_hash' => Hash::make($validated['password']),
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'] ?? null,
                'role_id' => $validated['role_id'],
                'employee_id' => $validated['employee_id'] ?? null,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            DB::commit();
            
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified user
     */
    public function show($userId)
    {
        // Single query with all related data
        $user = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.role_id')
            ->leftJoin('employees', 'users.employee_id', '=', 'employees.employee_id')
            ->where('users.user_id', $userId)
            ->select(
                'users.*',
                'roles.role_name',
                'roles.role_code',
                'roles.description as role_description',
                DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) as employee_name"),
                'employees.employee_code',
                'employees.email as employee_email'
            )
            ->first();
        
        if (!$user) {
            abort(404, 'User not found');
        }
        
        // Get recent activities
        $recentActivities = DB::table('activity_logs')
            ->where('user_id', $userId)
            ->select(
                'activity_id',
                'activity_type',
                'description',
                'module_name',
                'activity_timestamp'
            )
            ->orderByDesc('activity_timestamp')
            ->limit(20)
            ->get();
        
        return view('admin.users.show', compact('user', 'recentActivities'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($userId)
    {
        $user = DB::table('users')
            ->where('user_id', $userId)
            ->first();
        
        if (!$user) {
            abort(404, 'User not found');
        }
        
        $roles = Role::select('role_id', 'role_name', 'role_code')->get();
        
        $employees = DB::table('employees')
            ->leftJoin('users', function($join) use ($userId) {
                $join->on('employees.employee_id', '=', 'users.employee_id')
                     ->where('users.user_id', '!=', $userId);
            })
            ->whereNull('users.user_id')
            ->orWhere('employees.employee_id', $user->employee_id)
            ->select(
                'employees.employee_id',
                'employees.employee_code',
                DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) as full_name")
            )
            ->orderBy('employees.employee_code')
            ->get();
        
        return view('admin.users.edit', compact('user', 'roles', 'employees'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $userId)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:users,username,' . $userId . ',user_id'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email,' . $userId . ',user_id'],
            'full_name' => ['required', 'string', 'max:200'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role_id' => ['required', 'exists:roles,role_id'],
            'employee_id' => ['nullable', 'exists:employees,employee_id', 'unique:users,employee_id,' . $userId . ',user_id'],
            'is_active' => ['boolean'],
        ]);
        
        DB::table('users')
            ->where('user_id', $userId)
            ->update([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'] ?? null,
                'role_id' => $validated['role_id'],
                'employee_id' => $validated['employee_id'] ?? null,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'updated_at' => now(),
            ]);
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified user
     */
    public function destroy($userId)
    {
        // Prevent self-deletion
        if ($userId == Auth::user()->user_id) {
            return back()->with('error', 'You cannot delete your own account');
        }
        
        // Check if last admin (optimized query)
        $user = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.role_id')
            ->where('users.user_id', $userId)
            ->select('users.user_id', 'roles.role_code')
            ->first();
        
        if ($user->role_code === 'admin') {
            $adminCount = DB::table('users')
                ->join('roles', 'users.role_id', '=', 'roles.role_id')
                ->where('roles.role_code', 'admin')
                ->count();
            
            if ($adminCount <= 1) {
                return back()->with('error', 'Cannot delete the last admin user');
            }
        }
        
        DB::table('users')->where('user_id', $userId)->delete();
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, $userId)
    {
        $validated = $request->validate([
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);
        
        DB::table('users')
            ->where('user_id', $userId)
            ->update([
                'password_hash' => Hash::make($validated['new_password']),
                'updated_at' => now(),
            ]);
        
        return back()->with('success', 'Password reset successfully');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus($userId)
    {
        if ($userId == Auth::user()->user_id) {
            return back()->with('error', 'You cannot deactivate your own account');
        }
        
        DB::table('users')
            ->where('user_id', $userId)
            ->update([
                'is_active' => DB::raw('NOT is_active'),
                'updated_at' => now(),
            ]);
        
        return back()->with('success', 'User status updated successfully');
    }
}
