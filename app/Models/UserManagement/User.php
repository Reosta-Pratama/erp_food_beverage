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
     * Get role relationship
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
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
