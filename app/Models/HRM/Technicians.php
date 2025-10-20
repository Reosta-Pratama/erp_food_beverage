<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Technicians extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'technicians';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'technician_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'specialization',
        'certification_level',
        'is_available',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
