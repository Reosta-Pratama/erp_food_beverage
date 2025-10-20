<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class LeaveTypes extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'leave_types';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'leave_type_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'leave_type_name',
        'max_days_per_year',
        'is_paid',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
