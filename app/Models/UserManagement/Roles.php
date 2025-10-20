<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'roles';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'role_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'role_name',
        'role_code',
        'description',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
