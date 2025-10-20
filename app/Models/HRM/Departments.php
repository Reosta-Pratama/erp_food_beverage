<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'departments';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'department_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'department_name',
        'department_code',
        'manager_id',
        'description',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
