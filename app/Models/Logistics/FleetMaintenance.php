<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Model;

class FleetMaintenance extends Model
{
    //
    protected $table = 'fleet_maintenance';
    protected $primaryKey = 'fleet_maintenance_id';
    public $timestamps = false;

    protected $fillable = [
        'vehicle_id',
        'maintenance_date',
        'maintenance_type',
        'cost',
        'odometer_reading',
        'technician_id',
        'work_performed',
        'next_service_date',
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'cost' => 'decimal:2',
        'next_service_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
