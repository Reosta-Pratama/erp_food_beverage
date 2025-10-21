<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    //
    protected $table = 'leave_types';
    protected $primaryKey = 'leave_type_id';
    public $timestamps = false;

    protected $fillable = [
        'leave_type_name',
        'max_days_per_year',
        'is_paid',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
