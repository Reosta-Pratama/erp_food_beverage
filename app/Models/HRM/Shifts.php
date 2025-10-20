<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Shifts extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'shifts';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'shift_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'shift_name',
        'start_time',
        'end_time',
        'work_hours',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
