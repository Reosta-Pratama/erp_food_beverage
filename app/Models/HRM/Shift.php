<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    //
    protected $table = 'shifts';
    protected $primaryKey = 'shift_id';
    public $timestamps = false;

    protected $fillable = [
        'shift_name',
        'start_time',
        'end_time',
        'work_hours',
        'is_active',
    ];

    protected $casts = [
        'work_hours' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
