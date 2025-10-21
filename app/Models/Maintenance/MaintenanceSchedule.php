<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    //
    protected $table = 'maintenance_schedules';
    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'machine_id',
        'maintenance_type',
        'frequency',
        'interval_days',
        'last_maintenance',
        'next_maintenance',
        'assigned_technician_id',
        'status',
        'description',
    ];

    protected $casts = [
        'last_maintenance' => 'date',
        'next_maintenance' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
