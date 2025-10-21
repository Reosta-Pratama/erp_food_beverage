<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    //
    protected $table = 'leaves';
    protected $primaryKey = 'leave_id';
    public $timestamps = false;

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

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approval_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
