<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    //
    protected $table = 'role_permissions';
    protected $primaryKey = 'role_permission_id';
    public $timestamps = false;

    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
