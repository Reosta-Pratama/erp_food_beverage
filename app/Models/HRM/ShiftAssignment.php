<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class ShiftAssignment extends Model
{
    //
    protected $table = 'shift_assignments';
    protected $primaryKey = 'assignment_id';
    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'shift_id',
        'effective_date',
        'end_date',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
