<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
    protected $table = 'permissions';
    protected $primaryKey = 'permission_id';
    public $timestamps = false;

    protected $fillable = [
        'module_name',
        'permission_name',
        'permission_code',
        'can_create',
        'can_read',
        'can_update',
        'can_delete',
    ];

    protected $casts = [
        'can_create' => 'boolean',
        'can_read' => 'boolean',
        'can_update' => 'boolean',
        'can_delete' => 'boolean',
        'created_at' => 'datetime',
    ];
}
