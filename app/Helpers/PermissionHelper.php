<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PermissionHelper
{
    /**
     * Cache duration in minutes
     */
    private const CACHE_DURATION = 60;
    
    // Toggle debug mode
    private const DEBUG_MODE = true; // Set false di production

    private static function debug($label, $data)
    {
        if (!self::DEBUG_MODE) {
            return;
        }

        $output = "\n" . str_repeat('=', 80) . "\n";
        $output .= "🔍 PERMISSION DEBUG: {$label}\n";
        $output .= str_repeat('=', 80) . "\n";
        
        if (is_array($data) || is_object($data)) {
            $output .= self::formatArray((array)$data);
        } else {
            $output .= "Value: {$data}\n";
        }
        
        $output .= str_repeat('=', 80) . "\n";
        
        Log::channel('permissions')->info($output);
    }

    private static function formatArray(array $data, $indent = 0): string
    {
        $output = '';
        $indentStr = str_repeat('  ', $indent);
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $output .= "{$indentStr}📁 {$key}:\n";
                $output .= self::formatArray($value, $indent + 1);
            } elseif (is_object($value)) {
                $output .= "{$indentStr}📦 {$key}:\n";
                $output .= self::formatArray((array)$value, $indent + 1);
            } else {
                $valueStr = var_export($value, true);
                $output .= "{$indentStr}• {$key}: {$valueStr}\n";
            }
        }
        
        return $output;
    }

    private static $debugCallCounter = [];
    private static function debugWithCounter($label, $data)
    {
        if (!self::DEBUG_MODE) {
            return;
        }

        // Count calls
        if (!isset(self::$debugCallCounter[$label])) {
            self::$debugCallCounter[$label] = 0;
        }
        self::$debugCallCounter[$label]++;

        // Only log first 3 calls, then summarize
        if (self::$debugCallCounter[$label] <= 3) {
            self::debug($label, $data);

        } elseif (self::$debugCallCounter[$label] === 4) {

            $count = self::$debugCallCounter[$label];

            Log::channel('permissions')->info(
                "⚠️ '{$label}' called {$count} times (further calls suppressed to reduce log spam)"
            );
        }
    }

    /**
     * Get user's role code with caching
     */
    private static function getUserRoleCode(): ?string
    {
        if (!Auth::check()) {
            // self::debugWithCounter('getUserRoleCode', ['status' => 'Not authenticated']);
            return null;
        }

        $userId = Auth::id();
        
        $roleCode = Cache::remember("user_role_{$userId}", self::CACHE_DURATION, function () use ($userId) {
            $result = DB::table('users')
                ->join('roles', 'users.role_id', '=', 'roles.role_id')
                ->where('users.user_id', $userId)
                ->value('roles.role_code');
            
            // self::debug('getUserRoleCode [DB Query]', [
            //     'user_id' => $userId,
            //     'role_code' => $result,
            //     'cached' => false
            // ]);
            
            return $result;
        });
        
        return $roleCode;
    }

    /**
     * Get user's permissions with caching
     */
    private static function getUserPermissions(): array
    {
        if (!Auth::check()) {
            return [];
        }

        $userId = Auth::id();
        
        $permissions = Cache::remember("user_permissions_{$userId}", self::CACHE_DURATION, function () use ($userId) {
            $result = DB::table('users')
                ->join('roles', 'users.role_id', '=', 'roles.role_id')
                ->join('role_permissions', 'roles.role_id', '=', 'role_permissions.role_id')
                ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.permission_id')
                ->where('users.user_id', $userId)
                ->select(
                    'permissions.permission_code',
                    'permissions.can_create',
                    'permissions.can_read',
                    'permissions.can_update',
                    'permissions.can_delete'
                )
                ->get()
                ->keyBy('permission_code')
                ->toArray();
            
            // self::debug('getUserPermissions [DB Query - FRESH]', [
            //     'user_id' => $userId,
            //     'total_permissions' => count($result),
            //     'permission_list' => array_keys($result),
            //     'cached' => false
            // ]);
            
            return $result;
        });
        
        return $permissions;
    }

    /**
     * Get specific permission
     */
    private static function getPermission(string $permissionCode): ?object
    {
        $permissions = self::getUserPermissions();
        $permission = $permissions[$permissionCode] ?? null;
        
        // self::debugWithCounter("getPermission: {$permissionCode}", [
        //     'permission_code' => $permissionCode,
        //     'found' => $permission !== null,
        //     'details' => $permission ? [
        //         'can_create' => $permission->can_create,
        //         'can_read' => $permission->can_read,
        //         'can_update' => $permission->can_update,
        //         'can_delete' => $permission->can_delete,
        //     ] : null
        // ]);
        
        return $permission;
    }

    /**
     * Check if user has permission
     */
    public static function hasPermission(string $permissionCode): bool
    {
        $roleCode = self::getUserRoleCode();
        
        if (!$roleCode) {
            return false;
        }

        // Admin always has access
        if ($roleCode === 'admin') {
            return true;
        }

        $permission = self::getPermission($permissionCode);
        
        return $permission && (
            $permission->can_create || 
            $permission->can_read || 
            $permission->can_update || 
            $permission->can_delete
        );
    }

    /**
     * Check if user can create
     */
    public static function canCreate(string $permissionCode): bool
    {
        $roleCode = self::getUserRoleCode();
        
        if (!$roleCode) {
            return false;
        }

        if ($roleCode === 'admin') {
            return true;
        }

        $permission = self::getPermission($permissionCode);
        return $permission && $permission->can_create;
    }

    /**
     * Check if user can read
     */
    public static function canRead(string $permissionCode): bool
    {
        $roleCode = self::getUserRoleCode();

        if (!$roleCode) {
            return false;
        }

        if ($roleCode === 'admin') {
            return true;
        }

        $permission = self::getPermission($permissionCode);
        return $permission && $permission->can_read;
    }

    /**
     * Check if user can update
     */
    public static function canUpdate(string $permissionCode): bool
    {
        $roleCode = self::getUserRoleCode();
        
        if (!$roleCode) {
            return false;
        }

        if ($roleCode === 'admin') {
            return true;
        }

        $permission = self::getPermission($permissionCode);
        return $permission && $permission->can_update;
    }

    /**
     * Check if user can delete
     */
    public static function canDelete(string $permissionCode): bool
    {
        $roleCode = self::getUserRoleCode();
        
        if (!$roleCode) {
            return false;
        }

        if ($roleCode === 'admin') {
            return true;
        }

        $permission = self::getPermission($permissionCode);
        return $permission && $permission->can_delete;
    }

    /**
     * Check if user is admin
     */
    public static function isAdmin(): bool
    {
        return self::getUserRoleCode() === 'admin';
    }

    /**
     * Check if user is operator
     */
    public static function isOperator(): bool
    {
        return self::getUserRoleCode() === 'operator';
    }

    /**
     * Check if user is finance/HR
     */
    public static function isFinanceHR(): bool
    {
        return self::getUserRoleCode() === 'finance_hr';
    }

    /**
     * Clear user permission cache (call this when role/permissions are updated)
     */
    public static function clearCache(?int $userId = null): void
    {
        $userId = $userId ?? Auth::id();
        
        if ($userId) {
            Cache::forget("user_role_{$userId}");
            Cache::forget("user_permissions_{$userId}");
        }
    }
}