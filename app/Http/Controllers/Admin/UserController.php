<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LogsActivity;
use App\Models\UserManagement\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    use LogsActivity;
    
    /**
     * Display a listing of users
     */
    public function index(Request $request) 
    {
        // Log VIEW activity
        $this->logView('User Management - Users', 'Viewed users list');
        
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
        
        $roles = Role::select('role_id', 'role_name', 'role_code')->get();
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        // No logging needed for form display
        
        $roles = Role::select('role_id', 'role_name', 'role_code')->get();
        
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
        ], [
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes and underscores.',
            
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',
            
            'full_name.required' => 'Full name is required.',
            
            'role_id.required' => 'Please select a role.',
            'role_id.exists' => 'Selected role is invalid.',
            
            'employee_id.exists' => 'Selected employee is invalid.',
            'employee_id.unique' => 'This employee already has a user account.',
        ]);
        
        DB::beginTransaction();
        try {
            // Get role name for logging
            $role = DB::table('roles')->where('role_id', $validated['role_id'])->first();
            
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
            
            // Log CREATE action (exclude password from log)
            $this->logCreate(
                'User Management - Users',
                'users',
                $userId,
                [
                    'username' => $validated['username'],
                    'email' => $validated['email'],
                    'full_name' => $validated['full_name'],
                    'phone' => $validated['phone'] ?? null,
                    'role_name' => $role->role_name,
                    'employee_id' => $validated['employee_id'] ?? null,
                    'is_active' => $request->has('is_active') ? 1 : 0,
                ]
            );
            
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
        
        // Log VIEW activity
        $this->logView(
            'User Management - Users',
            "Viewed user: {$user->username} (ID: {$userId})"
        );
        
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
        // No logging needed for form display
        
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
        ], [
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken by another user.',
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes and underscores.',
            
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already used by another user.',
            
            'full_name.required' => 'Full name is required.',
            
            'role_id.required' => 'Please select a role.',
            'role_id.exists' => 'Selected role is invalid.',
            
            'employee_id.exists' => 'Selected employee is invalid.',
            'employee_id.unique' => 'This employee already has a user account.',
        ]);
        
        DB::beginTransaction();
        try {
            // Capture OLD data before update
            $oldUser = DB::table('users')
                ->join('roles', 'users.role_id', '=', 'roles.role_id')
                ->where('users.user_id', $userId)
                ->select(
                    'users.username',
                    'users.email',
                    'users.full_name',
                    'users.phone',
                    'users.role_id',
                    'users.employee_id',
                    'users.is_active',
                    'roles.role_name as old_role_name'
                )
                ->first();
            
            // Get new role name
            $newRole = DB::table('roles')->where('role_id', $validated['role_id'])->first();
            
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
            
            // Log UPDATE action
            $this->logUpdate(
                'User Management - Users',
                'users',
                $userId,
                [
                    'username' => $oldUser->username,
                    'email' => $oldUser->email,
                    'full_name' => $oldUser->full_name,
                    'phone' => $oldUser->phone,
                    'role_name' => $oldUser->old_role_name,
                    'employee_id' => $oldUser->employee_id,
                    'is_active' => $oldUser->is_active,
                ],
                [
                    'username' => $validated['username'],
                    'email' => $validated['email'],
                    'full_name' => $validated['full_name'],
                    'phone' => $validated['phone'] ?? null,
                    'role_name' => $newRole->role_name,
                    'employee_id' => $validated['employee_id'] ?? null,
                    'is_active' => $request->has('is_active') ? 1 : 0,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
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
        
        $user = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.role_id')
            ->where('users.user_id', $userId)
            ->select(
                'users.user_id',
                'users.username',
                'users.email',
                'users.full_name',
                'users.phone',
                'users.employee_id',
                'users.is_active',
                'roles.role_code',
                'roles.role_name'
            )
            ->first();
        
        if (!$user) {
            abort(404, 'User not found');
        }
        
        // Check if last admin
        if ($user->role_code === 'admin') {
            $adminCount = DB::table('users')
                ->join('roles', 'users.role_id', '=', 'roles.role_id')
                ->where('roles.role_code', 'admin')
                ->count();
            
            if ($adminCount <= 1) {
                return back()->with('error', 'Cannot delete the last admin user');
            }
        }
        
        DB::beginTransaction();
        try {
            DB::table('users')->where('user_id', $userId)->delete();
            
            // Log DELETE action
            $this->logDelete(
                'User Management - Users',
                'users',
                $userId,
                [
                    'username' => $user->username,
                    'email' => $user->email,
                    'full_name' => $user->full_name,
                    'phone' => $user->phone,
                    'role_name' => $user->role_name,
                    'employee_id' => $user->employee_id,
                    'is_active' => $user->is_active,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, $userId)
    {
        $validated = $request->validate([
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'new_password.required' => 'New password is required.',
            'new_password.confirmed' => 'Password confirmation does not match.',
            'new_password.min' => 'Password must be at least 8 characters.',
        ]);
        
        DB::beginTransaction();
        try {
            // Get user info for logging
            $user = DB::table('users')
                ->where('user_id', $userId)
                ->select('username', 'email')
                ->first();
            
            DB::table('users')
                ->where('user_id', $userId)
                ->update([
                    'password_hash' => Hash::make($validated['new_password']),
                    'updated_at' => now(),
                ]);
            
            // Log password reset as special audit action
            $this->logAudit(
                'PASSWORD_RESET',
                'User Management - Users',
                'users',
                $userId,
                null,
                [
                    'username' => $user->username,
                    'email' => $user->email,
                    'reset_by' => Auth::user()->username,
                    'reset_time' => now()->toDateTimeString(),
                ]
            );
            
            $this->logActivity(
                'Password Reset',
                "Reset password for user: {$user->username} (ID: {$userId})",
                'User Management - Users'
            );
            
            DB::commit();
            
            return back()->with('success', 'Password reset successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reset password: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus($userId)
    {
        if ($userId == Auth::user()->user_id) {
            return back()->with('error', 'You cannot deactivate your own account');
        }
        
        DB::beginTransaction();
        try {
            // Get current user data
            $user = DB::table('users')
                ->where('user_id', $userId)
                ->select('username', 'email', 'is_active')
                ->first();
            
            if (!$user) {
                abort(404, 'User not found');
            }
            
            $newStatus = !$user->is_active;
            
            DB::table('users')
                ->where('user_id', $userId)
                ->update([
                    'is_active' => $newStatus,
                    'updated_at' => now(),
                ]);
            
            // Log status toggle
            $this->logAudit(
                'STATUS_CHANGE',
                'User Management - Users',
                'users',
                $userId,
                ['is_active' => $user->is_active],
                ['is_active' => $newStatus]
            );
            
            $statusText = $newStatus ? 'activated' : 'deactivated';
            $this->logActivity(
                'Status Toggle',
                "User {$statusText}: {$user->username} (ID: {$userId})",
                'User Management - Users'
            );
            
            DB::commit();
            
            return back()->with('success', 'User status updated successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }
}