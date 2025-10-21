<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Model;

class DowntimeTracking extends Model
{
    //
    protected $table = 'downtime_tracking';
    protected $primaryKey = 'downtime_id';
    public $timestamps = false;

    protected $fillable = [
        'machine_id',
        'start_time',
        'end_time',
        'duration_hours',
        'downtime_reason',
        'downtime_category',
        'reported_by',
        'production_loss',
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_hours' => 'decimal:2',
        'production_loss' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
