<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    //
    protected $table = 'maintenance_requests';
    protected $primaryKey = 'request_id';

    protected $fillable = [
        'ticket_code',
        'machine_id',
        'requested_by',
        'request_date',
        'priority',
        'issue_type',
        'issue_description',
        'status',
        'assigned_to',
        'resolution_date',
        'resolution_notes',
    ];

    protected $casts = [
        'request_date' => 'date',
        'resolution_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
