<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    //
    /**
     * List permissions with role count
     */
    public function index(Request $request)
    {
        $query = DB::table('permissions')
            ->leftJoin('role_permissions', 'permissions.permission_id', '=', 'role_permissions.permission_id')
            ->select(
                'permissions.permission_id',
                'permissions.module_name',
                'permissions.permission_name',
                'permissions.permission_code',
                'permissions.can_create',
                'permissions.can_read',
                'permissions.can_update',
                'permissions.can_delete',
                'permissions.created_at',
                DB::raw('COUNT(DISTINCT role_permissions.role_id) as roles_count')
            )
            ->groupBy(
                'permissions.permission_id',
                'permissions.module_name',
                'permissions.permission_name',
                'permissions.permission_code',
                'permissions.can_create',
                'permissions.can_read',
                'permissions.can_update',
                'permissions.can_delete',
                'permissions.created_at'
            )
            ->orderByDesc('permissions.created_at');
        
        // Filter by module
        if ($request->filled('module')) {
            $query->where('permissions.module_name', $request->module);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('permissions.permission_name', 'like', "%{$search}%")
                  ->orWhere('permissions.permission_code', 'like', "%{$search}%");
            });
        }
        
        $permissions = $query->orderBy('permissions.module_name')
            ->orderBy('permissions.permission_name')
            ->paginate(10);
        
        // Get unique modules for filter
        $modules = DB::table('permissions')
            ->distinct()
            ->pluck('module_name')
            ->sort()
            ->values();

        return view('admin.permissions.index', compact('permissions', 'modules'));
    }

    /**
     * Create form
     */
    public function create()
    {
        $modules = DB::table('permissions')
            ->distinct()
            ->pluck('module_name')
            ->sort()
            ->values();
        
        return view('admin.permissions.create', compact('modules'));
    }

    /**
     * Store permission
     */
    public function store(Request $request)
    {
        $request->merge([
            'can_create' => $request->has('can_create'),
            'can_read'   => $request->has('can_read'),
            'can_update' => $request->has('can_update'),
            'can_delete' => $request->has('can_delete'),
        ]);

        $validated = $request->validate([
            'module_name' => ['required', 'string', 'max:100'],
            'permission_name' => ['required', 'string', 'max:150'],
            'permission_code' => ['required', 'string', 'max:150', 'unique:permissions,permission_code'],
            'can_create' => ['boolean'],
            'can_read' => ['boolean'],
            'can_update' => ['boolean'],
            'can_delete' => ['boolean'],
        ], [
            'module_name.required' => 'The module name is required.',
            'module_name.string' => 'The module name must be a valid text.',
            'module_name.max' => 'The module name cannot exceed 100 characters.',

            'permission_name.required' => 'The permission name is required.',
            'permission_name.string' => 'The permission name must be a valid text.',
            'permission_name.max' => 'The permission name cannot exceed 150 characters.',

            'permission_code.required' => 'The permission code is required.',
            'permission_code.string' => 'The permission code must be a valid text.',
            'permission_code.max' => 'The permission code cannot exceed 150 characters.',
            'permission_code.unique' => 'This permission code is already in use. Please choose another one.',

            'can_create.boolean' => 'The create permission value must be true or false.',
            'can_read.boolean' => 'The read permission value must be true or false.',
            'can_update.boolean' => 'The update permission value must be true or false.',
            'can_delete.boolean' => 'The delete permission value must be true or false.',
        ]);

        DB::beginTransaction();
        try {
            DB::table('permissions')->insert([
                'module_name' => $validated['module_name'],
                'permission_name' => $validated['permission_name'],
                'permission_code' => $validated['permission_code'],
                'can_create' => $validated['can_create'] ? 1 : 0,
                'can_read' => $validated['can_read'] ? 1 : 0,
                'can_update' => $validated['can_update'] ? 1 : 0,
                'can_delete' => $validated['can_delete'] ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            DB::commit();

            return redirect()
                ->route('admin.permissions.index')
                ->with('success', 'Permission created successfully');
        } catch (\Exception $e) {
             DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create permission: ' . $e->getMessage());
        }
    }

    /**
     * Show permission details with assigned roles
     */
    public function show($permissionId)
    {
        // Get permission with role count
        $permission = DB::table('permissions')
            ->leftJoin('role_permissions', 'permissions.permission_id', '=', 'role_permissions.permission_id')
            ->where('permissions.permission_id', $permissionId)
            ->select(
                'permissions.*',
                DB::raw('COUNT(DISTINCT role_permissions.role_id) as roles_count')
            )
            ->groupBy(
                'permissions.permission_id',
                'permissions.module_name',
                'permissions.permission_name',
                'permissions.permission_code',
                'permissions.can_create',
                'permissions.can_read',
                'permissions.can_update',
                'permissions.can_delete',
                'permissions.created_at',
                'permissions.updated_at'
            )
            ->first();
        
        if (!$permission) {
            abort(404, 'Permission not found');
        }
        
        // Get assigned roles
        $roles = DB::table('roles')
            ->join('role_permissions', 'roles.role_id', '=', 'role_permissions.role_id')
            ->where('role_permissions.permission_id', $permissionId)
            ->select(
                'roles.role_id',
                'roles.role_name',
                'roles.role_code',
                'roles.description'
            )
            ->orderBy('roles.role_name')
            ->get();
        
        return view('admin.permissions.show', compact('permission', 'roles'));
    }

    /**
     * Edit form
     */
    public function edit($permissionId)
    {
        $permission = DB::table('permissions')
            ->where('permission_id', $permissionId)
            ->first();
        
        if (!$permission) {
            abort(404, 'Permission not found');
        }
        
        $modules = DB::table('permissions')
            ->distinct()
            ->pluck('module_name')
            ->sort()
            ->values();
        
        return view('admin.permissions.edit', compact('permission', 'modules'));
    }

    /**
     * Update permission
     */
    public function update(Request $request, $permissionId)
    {
        $validated = $request->validate([
            'module_name' => ['required', 'string', 'max:100'],
            'permission_name' => ['required', 'string', 'max:150'],
            'permission_code' => ['required', 'string', 'max:150', 'unique:permissions,permission_code,' . $permissionId . ',permission_id'],
            'can_create' => ['boolean'],
            'can_read' => ['boolean'],
            'can_update' => ['boolean'],
            'can_delete' => ['boolean'],
        ]);
        
        DB::table('permissions')
            ->where('permission_id', $permissionId)
            ->update([
                'module_name' => $validated['module_name'],
                'permission_name' => $validated['permission_name'],
                'permission_code' => $validated['permission_code'],
                'can_create' => $request->has('can_create') ? 1 : 0,
                'can_read' => $request->has('can_read') ? 1 : 0,
                'can_update' => $request->has('can_update') ? 1 : 0,
                'can_delete' => $request->has('can_delete') ? 1 : 0,
                'updated_at' => now(),
            ]);
        
        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully');
    }
    
    /**
     * Delete permission
     */
    public function destroy($permissionId)
    {
        // Check if permission is assigned to any roles
        $roleCount = DB::table('role_permissions')
            ->where('permission_id', $permissionId)
            ->count();
        
        if ($roleCount > 0) {
            return back()->with('error', 'Cannot delete permission that is assigned to roles');
        }
        
        DB::table('permissions')
            ->where('permission_id', $permissionId)
            ->delete();
        
        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully');
    }
}
