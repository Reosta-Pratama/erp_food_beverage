<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Model;

class RouteAssignment extends Model
{
    //
    protected $table = 'route_assignments';
    protected $primaryKey = 'assignment_id';
    public $timestamps = false;

    protected $fillable = [
        'delivery_id',
        'route_id',
        'vehicle_id',
        'driver_id',
        'assignment_date',
    ];

    protected $casts = [
        'assignment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
