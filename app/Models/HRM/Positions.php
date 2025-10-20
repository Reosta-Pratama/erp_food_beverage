<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Positions extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'positions';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'position_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'position_name',
        'position_code',
        'department_id',
        'job_description',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
