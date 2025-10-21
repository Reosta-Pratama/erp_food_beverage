<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    //
    protected $table = 'vehicles';
    protected $primaryKey = 'vehicle_id';

    protected $fillable = [
        'vehicle_code',
        'vehicle_type',
        'license_plate',
        'manufacturer',
        'model',
        'capacity_kg',
        'fuel_consumption',
        'purchase_date',
        'insurance_expiry',
        'status',
    ];

    protected $casts = [
        'fuel_consumption' => 'decimal:2',
        'purchase_date' => 'date',
        'insurance_expiry' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
