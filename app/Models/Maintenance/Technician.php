<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    //
    protected $table = 'technicians';
    protected $primaryKey = 'technician_id';
    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'specialization',
        'certification_level',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
