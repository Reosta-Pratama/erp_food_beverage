<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Leaves extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'leaves';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'leave_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'approved_by',
        'approval_date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
