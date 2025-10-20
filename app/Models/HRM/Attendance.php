<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'attendance';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'attendance_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'status',
        'shift_id',
        'overtime_hours',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
