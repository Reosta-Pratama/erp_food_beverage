<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Model;

class MaintenanceHistory extends Model
{
    //
    protected $table = 'maintenance_history';
    protected $primaryKey = 'history_id';
    public $timestamps = false;

    protected $fillable = [
        'machine_id',
        'schedule_id',
        'maintenance_date',
        'maintenance_type',
        'technician_id',
        'duration_hours',
        'cost',
        'work_performed',
        'parts_replaced',
        'status',
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'duration_hours' => 'decimal:2',
        'cost' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
