<?php

namespace App\Models\UserManagement;

use App\Models\HRM\Employee;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method bool isAdmin()
 * @method bool isOperator()
 * @method bool isFinanceHr()
 */
class User extends Authenticatable
{
    //
    use HasApiTokens, Notifiable;

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
    ];

    protected $casts = [
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

    /**
     * Check if user has specific role
     */
    public function hasRole(string $roleCode): bool
    {
        return $this->role->role_code === $roleCode;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is operator
     */
    public function isOperator(): bool
    {
        return $this->hasRole('operator');
    }

    /**
     * Check if user is finance/hr
     */
    public function isFinanceHr(): bool
    {
        return $this->hasRole('finance_hr');
    }
}
