<?php

namespace App\Models\UserManagement;

use App\Models\HRM\Employee;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    //
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'full_name',
        'phone',
        'role_id',
        'employee_id',
        'is_active',
        'last_login',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_hash' => 'hashed',
        'is_active' => 'boolean',
        'last_login' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Override password field
     */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    /**
     * Get role relationship
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    /**
     * Get employee relationship
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    // ========================================
    // PERMISSION HELPER METHODS
    // ========================================

    /**
     * Check if user has specific permission
     */
    public function hasPermission(string $permissionCode): bool
    {
        // Admin always has permission
        if ($this->role->role_code === 'admin') {
            return true;
        }

        return $this->role->hasPermission($permissionCode);
    }

    /**
     * Check if user can create
     */
    public function canCreate(string $permissionCode): bool
    {
        if ($this->role->role_code === 'admin') {
            return true;
        }

        $permission = $this->role->permissions()
            ->where('permission_code', $permissionCode)
            ->first();

        return $permission && $permission->can_create;
    }

    /**
     * Check if user can read
     */
    public function canRead(string $permissionCode): bool
    {
        if ($this->role->role_code === 'admin') {
            return true;
        }

        $permission = $this->role->permissions()
            ->where('permission_code', $permissionCode)
            ->first();

        return $permission && $permission->can_read;
    }

    /**
     * Check if user can update
     */
    public function canUpdate(string $permissionCode): bool
    {
        if ($this->role->role_code === 'admin') {
            return true;
        }

        $permission = $this->role->permissions()
            ->where('permission_code', $permissionCode)
            ->first();

        return $permission && $permission->can_update;
    }

    /**
     * Check if user can delete
     */
    public function canDelete(string $permissionCode): bool
    {
        if ($this->role->role_code === 'admin') {
            return true;
        }

        $permission = $this->role->permissions()
            ->where('permission_code', $permissionCode)
            ->first();

        return $permission && $permission->can_delete;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role->role_code === 'admin';
    }

    /**
     * Check if user is operator
     */
    public function isOperator(): bool
    {
        return $this->role->role_code === 'operator';
    }

    /**
     * Check if user is finance/HR staff
     */
    public function isFinanceHR(): bool
    {
        return $this->role->role_code === 'finance_hr';
    }
}
