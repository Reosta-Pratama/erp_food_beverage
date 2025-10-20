<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class ShiftAssignments extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'shift_assignments';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'assignment_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'shift_id',
        'effective_date',
        'end_date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
