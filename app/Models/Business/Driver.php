<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    //
    protected $table = 'drivers';
    protected $primaryKey = 'driver_id';
    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'license_number',
        'license_type',
        'license_expiry',
        'is_available',
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'is_available' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
