<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'permissions';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'permission_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'module_name',
        'permission_name',
        'permission_code',
        'can_create',
        'can_read',
        'can_update',
        'can_delete',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
