<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'employees';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'employee_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'id_number',
        'department_id',
        'position_id',
        'join_date',
        'resign_date',
        'employment_status',
        'base_salary',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
