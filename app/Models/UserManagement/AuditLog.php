<?php

namespace App\Models\UserManagement;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    //
    protected $table = 'audit_logs';
    protected $primaryKey = 'audit_id';
    public $timestamps = false;

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

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'action_timestamp' => 'datetime',
    ];
}
