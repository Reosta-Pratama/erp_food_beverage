<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    //
    protected $table = 'attendance';
    protected $primaryKey = 'attendance_id';
    public $timestamps = false;

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

    protected $casts = [
        'attendance_date' => 'date',
        'overtime_hours' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
