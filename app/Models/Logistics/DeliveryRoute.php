<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Model;

class DeliveryRoute extends Model
{
    //
    protected $table = 'delivery_routes';
    protected $primaryKey = 'route_id';
    public $timestamps = false;

    protected $fillable = [
        'route_name',
        'route_code',
        'route_description',
        'distance_km',
        'estimated_duration_hours',
        'waypoints',
        'is_active',
    ];

    protected $casts = [
        'distance_km' => 'decimal:2',
        'estimated_duration_hours' => 'decimal:2',
        'waypoints' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
