<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Model;

class AuditLogs extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'audit_logs';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'audit_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'action_type',
        'module_name',
        'table_name',
        'record_id',
        'old_data',
        'new_data',
        'ip_address',
        'action_timestamp',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'action_timestamp' => 'datetime',
    ];
}
