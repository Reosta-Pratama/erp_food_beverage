<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    protected $table = 'departments';
    protected $primaryKey = 'department_id';
    public $timestamps = false;

    protected $fillable = [
        'department_name',
        'department_code',
        'manager_id',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
