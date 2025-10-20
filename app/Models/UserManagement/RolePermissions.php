<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Model;

class RolePermissions extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'role_permissions';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'role_permission_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
