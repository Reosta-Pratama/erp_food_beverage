<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HRM\Employee;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = User::with('role', 'employee');

        // Filter by role
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        $employees = Employee::where('employment_status', '!=', 'Resigned')
                        ->get()
                        ->map(function($employee) {
                            return [
                                'employee_id' => $employee->employee_id,
                                'full_name' => $employee->first_name . ' ' . $employee->last_name . ' (' . $employee->employee_code . ')'
                            ];
                        });

        return view('admin.users.create', compact('roles', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'full_name' => 'required|string|max:200',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,role_id',
            'employee_id' => 'nullable|exists:employees,employee_id',
            'is_active' => 'boolean',
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
            'employee_id' => $request->employee_id,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $employees = Employee::where('employment_status', '!=', 'Resigned')
                        ->get()
                        ->map(function($employee) {
                            return [
                                'employee_id' => $employee->employee_id,
                                'full_name' => $employee->first_name . ' ' . $employee->last_name . ' (' . $employee->employee_code . ')'
                            ];
                        });

        return view('admin.users.edit', compact('user', 'roles', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($user->user_id, 'user_id'),
            ],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($user->user_id, 'user_id'),
            ],
            'full_name' => 'required|string|max:200',
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,role_id',
            'employee_id' => 'nullable|exists:employees,employee_id',
            'is_active' => 'boolean',
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
            'employee_id' => $request->employee_id,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password_hash' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password berhasil direset');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Prevent deactivating own account
        if ($user->user_id === Auth::user()->user_id) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri');
        }

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        $message = $user->is_active ? 'User berhasil diaktifkan' : 'User berhasil dinonaktifkan';

        return back()->with('success', $message);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting own account
        if ($user->user_id === Auth::user()->user_id) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
