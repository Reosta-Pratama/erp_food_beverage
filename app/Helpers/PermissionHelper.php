<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Check if user has permission
     */
    public static function hasPermission(string $permissionCode): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        
        // Admin always has access
        if ($user->role->role_code === 'admin') {
            return true;
        }

        return $user->role->hasPermission($permissionCode);
    }

    /**
     * Check if user can create
     */
    public static function canCreate(string $permissionCode): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        
        if ($user->role->role_code === 'admin') {
            return true;
        }

        $permission = $user->role->permissions()
            ->where('permission_code', $permissionCode)
            ->first();

        return $permission && $permission->can_create;
    }

    /**
     * Check if user can read
     */
    public static function canRead(string $permissionCode): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        
        if ($user->role->role_code === 'admin') {
            return true;
        }

        $permission = $user->role->permissions()
            ->where('permission_code', $permissionCode)
            ->first();

        return $permission && $permission->can_read;
    }

    /**
     * Check if user can update
     */
    public static function canUpdate(string $permissionCode): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        
        if ($user->role->role_code === 'admin') {
            return true;
        }

        $permission = $user->role->permissions()
            ->where('permission_code', $permissionCode)
            ->first();

        return $permission && $permission->can_update;
    }

    /**
     * Check if user can delete
     */
    public static function canDelete(string $permissionCode): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        
        if ($user->role->role_code === 'admin') {
            return true;
        }

        $permission = $user->role->permissions()
            ->where('permission_code', $permissionCode)
            ->first();

        return $permission && $permission->can_delete;
    }

    /**
     * Check if user is admin
     */
    public static function isAdmin(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->role->role_code === 'admin';
    }

    /**
     * Check if user is operator
     */
    public static function isOperator(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->role->role_code === 'operator';
    }

    /**
     * Check if user is finance/HR
     */
    public static function isFinanceHR(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->role->role_code === 'finance_hr';
    }
}