<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserManagement\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    //
    /**
     * List roles with counts
     */
    public function index()
    {
        $roles = DB::table('roles')
            ->leftJoin('users', 'roles.role_id', '=', 'users.role_id')
            ->leftJoin('role_permissions', 'roles.role_id', '=', 'role_permissions.role_id')
            ->select(
                'roles.role_id',
                'roles.role_name',
                'roles.role_code',
                'roles.description',
                'roles.created_at',
                DB::raw('COUNT(DISTINCT users.user_id) as users_count'),
                DB::raw('COUNT(DISTINCT role_permissions.permission_id) as permissions_count')
            )
            ->groupBy(
                'roles.role_id',
                'roles.role_name',
                'roles.role_code',
                'roles.description',
                'roles.created_at'
            )
            ->get();
        
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Create form
     */
    public function create()
    {
        $permissions = Permission::select('permission_id', 'module_name', 'permission_name', 'permission_code', 
            'can_create', 'can_read', 'can_update', 'can_delete')
            ->orderBy('module_name')
            ->orderBy('permission_name')
            ->get()
            ->groupBy('module_name');

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store role
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_name' => ['required', 'string', 'max:100', 'unique:roles,role_name'],
            'role_code' => ['required', 'string', 'max:100', 'unique:roles,role_code', 'alpha_dash'],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,permission_id'],
        ], [
            'role_name.required' => 'The role name is required.',
            'role_name.string' => 'The role name must be a valid text.',
            'role_name.max' => 'The role name may not be longer than 100 characters.',
            'role_name.unique' => 'This role name is already taken. Please choose another one.',

            'role_code.required' => 'The role code is required.',
            'role_code.string' => 'The role code must be a valid text.',
            'role_code.max' => 'The role code may not be longer than 100 characters.',
            'role_code.unique' => 'This role code already exists. Please use a different code.',
            'role_code.alpha_dash' => 'The role code may only contain letters, numbers, dashes, and underscores.',

            'description.string' => 'The description must be valid text.',

            'permissions.array' => 'Invalid permission format. Please try again.',
            'permissions.*.exists' => 'One or more selected permissions are invalid.',
        ]);
        
        DB::beginTransaction();
        try {
            $roleId = DB::table('roles')->insertGetId([
                'role_name' => $validated['role_name'],
                'role_code' => $validated['role_code'],
                'description' => $validated['description'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            
            // Attach permissions
            if (!empty($validated['permissions'])) {
                $permissionData = array_map(function($permId) use ($roleId) {
                    return [
                        'role_id' => $roleId,
                        'permission_id' => $permId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }, $validated['permissions']);

                DB::table('role_permissions')->insert($permissionData);
            }
            
            DB::commit();
            
            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Role created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create role: ' . $e->getMessage());
        }
    }

    /**
     * Show role details
     */
    public function show($roleCode)
    {
        $role = DB::table('roles')
            ->leftJoin('users', 'roles.role_id', '=', 'users.role_id')
            ->where('roles.role_code', $roleCode)
            ->select(
                'roles.*',
                DB::raw('COUNT(users.user_id) as users_count')
            )
            ->groupBy(
                'roles.role_id',
                'roles.role_name',
                'roles.role_code',
                'roles.description',
                'roles.created_at',
                'roles.updated_at'
            )
            ->first();
        
        if (!$role) {
            abort(404, 'Role not found');
        }
        
        // Get permissions grouped by module
        $permissions = DB::table('permissions')
            ->join('role_permissions', 'permissions.permission_id', '=', 'role_permissions.permission_id')
            ->where('role_permissions.role_id', $role->role_id)
            ->select(
                'permissions.permission_id',
                'permissions.module_name',
                'permissions.permission_name',
                'permissions.permission_code',
                'permissions.can_create',
                'permissions.can_read',
                'permissions.can_update',
                'permissions.can_delete'
            )
            ->orderBy('permissions.module_name')
            ->orderBy('permissions.permission_name')
            ->get()
            ->groupBy('module_name');
        
        return view('admin.roles.show', compact('role', 'permissions'));
    }

    /**
     * Edit role
     */
    public function edit($roleCode)
    {
        $role = DB::table('roles')->where('role_code', $roleCode)->first();
        
        if (!$role) {
            abort(404, 'Role not found');
        }

        $permissions = Permission::select('permission_id', 'module_name', 'permission_name', 'permission_code', 
            'can_create', 'can_read', 'can_update', 'can_delete')
            ->orderBy('module_name')
            ->orderBy('permission_name')
            ->get()
            ->groupBy('module_name');
        
        $rolePermissions = DB::table('role_permissions')
            ->where('role_id', $role->role_id)
            ->pluck('permission_id')
            ->toArray();
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update role
     */
    public function update(Request $request, $roleCode)
    {
        $validated = $request->validate([
            'role_name' => ['required', 'string', 'max:100', 'unique:roles,role_name,' . $roleCode . ',role_code'],
            'role_code' => ['required', 'string', 'max:100', 'unique:roles,role_code,' . $roleCode . ',role_code', 'alpha_dash'],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,permission_id'],
        ], [
            'role_name.required' => 'Please enter a role name.',
            'role_name.string' => 'The role name must be valid text.',
            'role_name.max' => 'The role name can’t be longer than 100 characters.',
            'role_name.unique' => 'This role name is already in use. Please choose a different one.',

            'role_code.required' => 'Please enter a role code.',
            'role_code.string' => 'The role code must be valid text.',
            'role_code.max' => 'The role code can’t be longer than 100 characters.',
            'role_code.unique' => 'This role code already exists. Please use another one.',
            'role_code.alpha_dash' => 'The role code may only contain letters, numbers, dashes, and underscores.',

            'description.string' => 'The description must be valid text.',

            'permissions.array' => 'Invalid permission format. Please try again.',
            'permissions.*.exists' => 'One or more selected permissions are invalid.',
        ]);

        DB::beginTransaction();
        try {
            $roleId = DB::table('roles')
                ->where('role_code', $roleCode)
                ->value('role_id');

            DB::table('roles')
                ->where('role_id', $roleId)
                ->update([
                    'role_name' => $validated['role_name'],
                    'role_code' => $validated['role_code'],
                    'description' => $validated['description'] ?? null,
                    'updated_at' => now(),
                ]);

            // Sync permissions
            DB::table('role_permissions')->where('role_id', $roleId)->delete();
            
            if (!empty($validated['permissions'])) {
                $permissionData = array_map(function($permId) use ($roleId) {
                    return [
                        'role_id' => $roleId,
                        'permission_id' => $permId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }, $validated['permissions']);
                
                DB::table('role_permissions')->insert($permissionData);
            }
            
            DB::commit();
            
            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Role updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update role: ' . $e->getMessage());
        }
    }

    /**
     * Delete role
     */
    public function destroy($roleCode)
    {
        $role = DB::table('roles')->where('role_code', $roleCode)->first();
        
        if (!$role) {
            abort(404, 'Role not found');
        }

        // Prevent deleting default roles
        if (in_array($role->role_code, ['admin', 'operator', 'finance_hr'])) {
            return back()->with('error', 'Cannot delete default system roles');
        }

        // Check if role has users
        $userCount = DB::table('users')->where('role_id', $role->role_id)->count();
        if ($userCount > 0) {
            return back()->with('error', 'Cannot delete role that has assigned users');
        }

        DB::beginTransaction();
        try {
            DB::table('role_permissions')->where('role_id', $role->role_id)->delete();
            DB::table('roles')->where('role_id', $role->role_id)->delete();
            
            DB::commit();
            
            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Role deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete role: ' . $e->getMessage());
        }
    }
}
