<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    //
    protected $table = 'positions';
    protected $primaryKey = 'position_id';
    public $timestamps = false;

    protected $fillable = [
        'position_name',
        'position_code',
        'department_id',
        'job_description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
